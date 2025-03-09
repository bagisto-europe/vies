<?php

namespace Bagisto\Vies\Listeners;

use Bagisto\Vies\Services\TaxCalculator;
use Webkul\Checkout\Models\CartItem;

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
        if ($event instanceof CartItem) {
            $this->taxCalculator->applyReverseChargeVAT($event->cart);
        } elseif (method_exists($event, 'getAttribute')) {
            $this->taxCalculator->applyReverseChargeVAT($event);
        }
    }
}
