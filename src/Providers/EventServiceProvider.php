<?php

namespace Bagisto\Vies\Providers;

use Bagisto\Vies\Listeners\ApplyReverseChargeVAT;
use Bagisto\Vies\Listeners\UpdateCompanyName;
use Bagisto\Vies\Listeners\VatValidation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'checkout.cart.calculate.items.tax.after' => [
            ApplyReverseChargeVAT::class,
        ],
        'customer.addresses.create.before' => [
            VatValidation::class,
        ],
        'customer.addresses.create.after' => [
            UpdateCompanyName::class,
        ],
        'customer.addresses.update.after' => [
            UpdateCompanyName::class,
            VatValidation::class,
        ],
    ];
}
