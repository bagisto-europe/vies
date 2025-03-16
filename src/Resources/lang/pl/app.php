<?php

return [
    'admin' => [
        'sales' => [
            'taxes' => [
                'vies' => [
                    'validation'                 => 'Weryfikacja VIES',
                    'validation_info'            => 'Włącz lub wyłącz weryfikację VIES dla numerów VAT UE, aby zapewnić ważność numerów VAT w UE.',
                    'status'                     => 'Status',
                    'status_info'                => 'Włącz lub wyłącz weryfikację VIES.',
                    'update_company_name'        => 'Zaktualizuj nazwę firmy',
                    'update_company_name_info'   => 'Jeśli włączone, nazwa firmy klienta zostanie zaktualizowana na podstawie odpowiedzi VIES.',
                    'exclude_product_types'      => 'Wyklucz typy produktów z odwrotnego obciążenia',
                    'exclude_product_types_info' => 'Określ, które typy produktów mają zostać wykluczone z odwrotnego obciążenia VAT.',
                ],
            ],
        ],
    ],
    'customers' => [
        'account' => [
            'addresses' => [
                'invalid-vat' => 'Podany numer VAT :vat jest nieprawidłowy. Proszę sprawdzić numer VAT i spróbować ponownie.',
            ],
        ],
    ],
];
