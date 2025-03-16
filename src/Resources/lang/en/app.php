<?php

return [
    'admin' => [
        'sales' => [
            'taxes' => [
                'vies' => [
                    'validation'                 => 'VIES Validation',
                    'validation_info'            => 'Enable or disable VIES validation for EU VAT numbers to ensure the validity of VAT numbers within the EU.',
                    'status'                     => 'Status',
                    'status_info'                => 'Toggle to enable or disable VIES validation.',
                    'update_company_name'        => 'Update Company Name',
                    'update_company_name_info'   => 'If enabled, the customer company name will be updated based on the VIES response.',
                    'exclude_product_types'      => 'Exclude Product Types from Reverse Charge',
                    'exclude_product_types_info' => 'Specify which product types should be excluded from Reverse Charge VAT.',
                ],
            ],
        ],
    ],
    'customers' => [
        'account' => [
            'addresses' => [
                'invalid-vat' => 'The provided VAT ID :vat is invalid. Please check the VAT number and try again.',
            ],
        ],
    ],
];
