<?php

namespace Bagisto\Vies\Providers;

use Bagisto\Vies\Listeners\ApplyReverseChargeVAT;
use Bagisto\Vies\Listeners\ValidateCustomerAddress;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'checkout.cart.calculate.items.tax.before' => [
            ApplyReverseChargeVAT::class,
        ],
        'checkout.cart.update.after' => [
            ApplyReverseChargeVAT::class,
        ],
        'customer.addresses.create.after' => [
            ValidateCustomerAddress::class,
        ],
        'customer.addresses.update.after' => [
            ValidateCustomerAddress::class,
        ],
    ];
}
