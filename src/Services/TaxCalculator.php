<?php

namespace Bagisto\Vies\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\CustomerAddress;

class TaxCalculator
{
    protected bool $enableValidation;

    protected string $viesApiUrl;

    protected int $cacheDuration = 86400;

    public function __construct()
    {
        $this->enableValidation = core()->getConfigData('sales.taxes.vies.status');
        $this->viesApiUrl = config('vies.vies_api_url');
    }

    /**
     * Checks if the given country is part of the European Union.
     *
     * @param  string  $country  The country code to check.
     * @return bool `true` if the country is in the EU, `false` otherwise.
     */
    public function isEuCountry($country): bool
    {
        try {
            $euCountries = Cache::get('eu_countries');

            if (! $euCountries) {
                $response = Http::get($this->viesApiUrl.'/check-status');

                if ($response->failed()) {
                    Log::error("VIES API Error: {$response->body()}");

                    return false;
                }

                $data = $response->json();

                $euCountries = array_map(function ($countryData) {
                    return $countryData['countryCode'];
                }, $data['countries']);

                Cache::put('eu_countries', $euCountries, $this->cacheDuration);
            }

            return in_array(strtoupper($country), $euCountries);

        } catch (\Exception $e) {
            Log::error('VIES API Exception: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Applies reverse charge VAT to eligible items in the cart.
     *
     * @param  \Webkul\Checkout\Models\Cart  $cart  The cart object containing items to process.
     */
    public function applyReverseChargeVAT(Cart $cart): void
    {
        $storeCountry = core()->getConfigData('sales.shipping.origin.country');
        if (! $this->enableValidation || ! $storeCountry) {
            return;
        }

        $customer = $cart->customer;
        $customerAddress = CustomerAddress::where('customer_id', $customer->id)->first();
        if (! $customerAddress || ! $customerAddress->vat_id) {
            return;
        }

        $countryCode = strtoupper($customerAddress->country);
        $isEuCustomer = $this->isEuCountry($countryCode);

        if ($countryCode && ! $isEuCustomer) {
            return;
        }

        if ($this->validateVAT($customerAddress->vat_id, $countryCode) && $countryCode !== $storeCountry) {
            $excludedProductTypes = $this->getExcludedProductTypes();

            foreach ($cart->items as $item) {
                if ($this->isItemExcluded($item, $excludedProductTypes)) {
                    continue;
                }

                $this->setZeroTaxRate($item);
            }
        }
    }

    /**
     * Retrieves the list of product types excluded from reverse charge VAT.
     */
    private function getExcludedProductTypes(): array
    {
        $excludedTypes = core()->getConfigData('sales.taxes.vies.exclude_product_types');

        return is_string($excludedTypes) ? array_map('trim', explode(',', $excludedTypes)) : (array) $excludedTypes;
    }

    /**
     * Checks if the given item is excluded from reverse charge VAT.
     *
     * Compares the item’s type with the excluded product types to determine if it should be excluded.
     *
     * @param  \Webkul\Checkout\Models\CartItem  $item  The cart item to check.
     * @param  array  $excludedTypes  The list of product types excluded from reverse charge VAT.
     */
    private function isItemExcluded(CartItem $item, array $excludedTypes): bool
    {
        return in_array(strtolower($item->type), array_map('strtolower', $excludedTypes));
    }

    /**
     * Validates a VAT number using the VIES API.
     *
     * @param  string|null  $vatNumber  The VAT number to validate.
     * @param  string|null  $countryCode  The country code associated with the VAT number.
     */
    public function validateVAT(?string $vatNumber, ?string $countryCode): bool
    {
        $vatNumber = preg_replace("/^$countryCode/", '', $vatNumber);

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

            $response = Http::retry(3, 100)->post("{$this->viesApiUrl}/check-vat-number", $payload);
            $result = $response->json();

            return $result['valid'] ?? false;
        } catch (\Exception $e) {
            Log::error('VIES API Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Retrieves VAT details from the VIES API.
     *
     * @param  string|null  $vatNumber  The VAT number to validate.
     * @param  string|null  $countryCode  The country code associated with the VAT number.
     * @return array|false The API response on success, or `false` on failure.
     */
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

    /**
     * Sets the tax rate to 0% for the given cart item.
     *
     * @param  \Webkul\Checkout\Models\CartItem  $item  The cart item to update.
     */
    private function setZeroTaxRate(CartItem $item): void
    {
        $item->update([
            'applied_tax_rate'    => null,
            'tax_percent'         => 0,
            'tax_amount'          => 0,
            'base_tax_amount'     => 0,
            'total_incl_tax'      => $item->total,
            'base_total_incl_tax' => $item->base_total,
            'price_incl_tax'      => $item->price,
            'base_price_incl_tax' => $item->base_price,
        ]);
    }
}
