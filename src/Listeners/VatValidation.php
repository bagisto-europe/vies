<?php

namespace Bagisto\Vies\Listeners;

use Bagisto\Vies\Services\TaxCalculator;
use Illuminate\Support\Facades\Request;

class VatValidation
{
    protected $taxCalculator;

    public function __construct(TaxCalculator $taxCalculator)
    {
        $this->taxCalculator = $taxCalculator;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        $status = core()->getConfigData('sales.taxes.vies.status');

        if (! $status) {
            return;
        }

        $countryCode = strtoupper(Request::input('country'));
        $vatId = Request::input('vat_id');

        $isEuCustomer = $this->taxCalculator->isEuCountry($countryCode);

        if ($vatId && $isEuCustomer) {
            $vatNumber = preg_replace("/^$countryCode/", '', $vatId);
            $isValidVat = $this->taxCalculator->validateVAT($vatId, $countryCode);

            if (! $isValidVat) {
                session()->flash('error', trans('vies::app.customers.account.addresses.invalid-vat', ['vat' => $vatId]));

                abort(redirect()->back());
            }
        }
    }
}
