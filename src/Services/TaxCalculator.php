<?php

namespace Bagisto\Vies\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Models\Cart;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Tax\Facades\Tax;

class TaxCalculator
{
    protected bool $enableValidation;

    protected string $viesApiUrl;

    public function __construct()
    {
        $this->enableValidation = core()->getConfigData('sales.taxes.vies.status');

        $this->viesApiUrl = config('vies.vies_api_url');
    }

    public function isViesServiceAvailable()
    {
        $response = Http::get($this->viesApiUrl . '/check-status');

        return $response->json()['vow']['available'] ?? false;
    }

    public function isEuCountry($country): bool
    {
        try {
            $response = Http::get($this->viesApiUrl . '/check-status');

            if ($response->failed()) {
                Log::error("VIES API Error: {$response->body()}");

                return false;
            }

            $data = $response->json();

            $euCountries = array_map(function ($countryData) {
                return $countryData['countryCode'];
            }, $data['countries']);

            return in_array(strtoupper($country), $euCountries);
        } catch (\Exception $e) {
            Log::error('VIES API Exception: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Applies Reverse Charge VAT to a customer's cart based on their VAT number and country.
     *
     * The method checks if the customer is from a different EU country than the store
     * and has a valid VAT number. If the customer meets these conditions, Reverse Charge
     * VAT is applied, which means the tax rate is set to 0% for the relevant items in the cart.
     *
     * If any of the above conditions are not met, standard VAT calculations will be used by Bagisto.
     *
     * @param  Webkul\Checkout\Models\Cart  $cart  The cart object containing the customer's items and associated details.
     * @return void
     *
     * @throws \Exception If any errors occur during the validation process or API calls.
     */
    public function applyReverseChargeVAT(Cart $cart)
    {
        $storeCountry = core()->getConfigData('sales.shipping.origin.country');

        if (! $this->enableValidation) {
            Log::warning('This is not a production environment, skipping Reverse Charge VAT');

            return;
        }

        if (! $storeCountry) {
            Log::warning('The shipping country has not been configured');

            return;
        }

        $customer = $cart->customer;
        $customerAddress = CustomerAddress::where('customer_id', $customer->id)->first();

        if (! $customerAddress || ! $customerAddress->vat_id) {
            Log::warning("No address or vat number found for customer ID: {$customer->id}");

            return;
        }

        $countryCode = strtoupper($customerAddress->country);
        $isEuCustomer = $this->isEuCountry($countryCode);

        if (! $isEuCustomer) {
            Log::info("Non-EU customer detected for customer ID: {$customer->id}");

            return;
        }

        $fullVatNumber = $customerAddress->vat_id;

        // Ensure VAT number is properly formatted
        if (str_starts_with($fullVatNumber, $countryCode)) {
            $vatNumber = substr($fullVatNumber, strlen($countryCode));
        } else {
            $vatNumber = $fullVatNumber;
        }

        if ($this->validateVAT($vatNumber, $countryCode) && $countryCode !== core()->getConfigData('sales.shipping.origin.country')) {

            foreach ($cart->items as $item) {
                $this->setZeroTaxRate($item);
            }
        }
    }

    public function validateVAT(?string $vatNumber, ?string $countryCode): bool
    {
        if (! $vatNumber || ! $countryCode) {
            Log::warning("Invalid VAT request: VAT=$vatNumber, Country=$countryCode");

            return false;
        }

        try {
            $payload = [
                'countryCode'              => $countryCode,
                'vatNumber'                => $vatNumber,
                'requesterMemberStateCode' => core()->getConfigData('sales.shipping.origin.country'),
                'requesterNumber'          => core()->getConfigData('sales.shipping.origin.vat_number'),
            ];

            $response = Http::retry(3, 100)->post($this->viesApiUrl.'/check-vat-number', $payload);

            $result = $response->json();
            Log::info("VIES API result for {$vatNumber}: ".($result['valid'] ? 'Valid' : 'Invalid'));

            return $result['valid'] ?? false;
        } catch (\Exception $e) {
            Log::error('VIES API Error: '.$e->getMessage());

            return false;
        }
    }

    public function GetDetails(?string $vatNumber, ?string $countryCode): array
    {
        if (! $vatNumber || ! $countryCode) {
            Log::warning("Invalid VAT request: VAT=$vatNumber, Country=$countryCode");

            return false;
        }

        try {
            $payload = [
                'countryCode'              => $countryCode,
                'vatNumber'                => $vatNumber,
                'requesterMemberStateCode' => core()->getConfigData('sales.shipping.origin.country'),
                'requesterNumber'          => core()->getConfigData('sales.shipping.origin.vat_number'),
            ];

            $response = Http::retry(3, 100)->post($this->viesApiUrl.'/check-vat-number', $payload);

            $result = $response->json();

            return $result;
        } catch (\Exception $e) {
            Log::error('VIES API Error: '.$e->getMessage());

            return false;
        }
    }

    private function setZeroTaxRate($item)
    {
        $item->applied_tax_rate = null;
        $item->tax_percent = 0;
        $item->tax_amount = 0;
        $item->base_tax_amount = 0;
        $item->total_incl_tax = $item->total;
        $item->base_total_incl_tax = $item->base_total;
        $item->price_incl_tax = $item->price;
        $item->base_price_incl_tax = $item->base_price;

        Log::info("Set tax to 0% for item ID: {$item->id}");
    }
}
