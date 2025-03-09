<?php

namespace Bagisto\Vies\Listeners;

use Bagisto\Vies\Services\TaxCalculator;
use Illuminate\Support\Facades\Log;

class ValidateCustomerAddress
{
    private $taxCalculator;

    public function __construct(TaxCalculator $taxCalculator)
    {
        $this->taxCalculator = $taxCalculator;
    }

    public function handle($event)
    {
        $status = core()->getConfigData('sales.taxes.vies.status');

        $ValidateCompany = core()->getConfigData('sales.taxes.vies.validate_company_name');

        if (! $status && ! $ValidateCompany) {
            return;
        }

        $customerAddress = $event;
        $customer = $customerAddress->customer;

        $countryCode = strtoupper($customerAddress->country);

        if ($this->taxCalculator->isEuCountry($countryCode)) {
            if (! $customerAddress->vat_id) {
                return;
            }

            $fullVatNumber = $customerAddress->vat_id;

            if (str_starts_with($fullVatNumber, $countryCode)) {
                $vatNumber = substr($fullVatNumber, strlen($countryCode));
            } else {
                $vatNumber = $fullVatNumber;
            }

            if ($this->taxCalculator->validateVAT($vatNumber, $countryCode)) {

                $vatDetails = $this->taxCalculator->GetDetails($vatNumber, $countryCode);

                if ($vatDetails) {
                    $customerAddress->company_name = $vatDetails['name'];
                    $customerAddress->save();

                    Log::info("VAT details updated for customer ID: {$customer->id}");
                }
            } else {
                Log::warning("Invalid VAT number for customer ID: {$customer->id}.");
            }
        }
    }
}
