<?php

return [
    [
        'key'    => 'sales.taxes.vies',
        'name'   => 'vies::app.admin.sales.taxes.vies.validation',
        'info'   => 'vies::app.admin.sales.taxes.vies.validation_info',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'status',
                'title' => 'vies::app.admin.sales.taxes.vies.status',
                'info'  => 'vies::app.admin.sales.taxes.vies.status_info',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'update_company_name',
                'title' => 'vies::app.admin.sales.taxes.vies.update_company_name',
                'info'  => 'vies::app.admin.sales.taxes.vies.update_company_name_info',
                'type'  => 'boolean',
            ],
            [
                'name'    => 'exclude_product_types',
                'title'   => 'vies::app.admin.sales.taxes.vies.exclude_product_types',
                'info'    => 'vies::app.admin.sales.taxes.vies.exclude_product_types_info',
                'type'    => 'multiselect',
                'options' => \Bagisto\Vies\Helpers\ProductHelper::getProductTypes(),
            ],
        ],
    ],
];
