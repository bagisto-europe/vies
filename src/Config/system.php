<?php

return [
    [
        'key'    => 'sales.taxes.vies',
        'name'   => 'VIES Validation',
        'info'   => 'Enable or disable VIES validation for EU VAT numbers to ensure the validity of VAT numbers within the EU.',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'status',
                'title' => 'Status',
                'info'  => 'Toggle to enable or disable VIES validation.',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'validate_company_name',
                'title' => 'Validate & Update Company Name',
                'info'  => 'If enabled, the company name will be updated based on the VIES response.',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'exclude_product_types',
                'title' => 'Exclude Product Types from Reverse Charge',
                'info'  => 'Specify which product types should be excluded from Reverse Charge VAT.',
                'type'  => 'multiselect',
                'options' => [
                    ['title' => 'Simple', 'value' => 'simple'],
                    ['title' => 'Virtual', 'value' => 'virtual'],
                    ['title' => 'Downloadable', 'value' => 'downloadable'],
                ],
            ],
        ],
    ],
];
