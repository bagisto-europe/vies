<?php

namespace Bagisto\Vies\Listeners;

use Bagisto\Vies\Services\TaxCalculator;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Models\Cart;

class ApplyReverseChargeVAT
{
    protected TaxCalculator $taxCalculator;

    public function __construct(TaxCalculator $taxCalculator)
    {
        $this->taxCalculator = $taxCalculator;
    }

    /**
     * Handle the event when tax calculation happens.
     */
    public function handle($event)
    {
        $status = core()->getConfigData('sales.taxes.vies.status');

        if (! $status) {
            return;
        }

        if ($event instanceof Cart) {
            Log::debug('VIES: Processing Cart object directly');
            $this->taxCalculator->applyReverseChargeVAT($event);
        }
    }
}
