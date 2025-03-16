<?php

return [
    'admin' => [
        'sales' => [
            'taxes' => [
                'vies' => [
                    'validation'                 => 'VIES Validatie',
                    'validation_info'            => 'Schakel VIES-validatie in of uit voor EU BTW-nummers om de geldigheid van BTW-nummers binnen de EU te waarborgen.',
                    'status'                     => 'Status',
                    'status_info'                => 'Schakel VIES-validatie in of uit.',
                    'update_company_name'        => 'Werk Bedrijfsnaam bij',
                    'update_company_name_info'   => 'Als ingeschakeld, wordt de bedrijfsnaam van de klant bijgewerkt op basis van het VIES-antwoord.',
                    'exclude_product_types'      => 'Exclusief Producttypen voor Omgekeerde Aangifte',
                    'exclude_product_types_info' => 'Specificeer welke producttypen moeten worden uitgesloten van de omgekeerde aangifte BTW.',
                ],
            ],
        ],
    ],
    'customers' => [
        'account' => [
            'addresses' => [
                'invalid-vat' => 'Het opgegeven btw-nummer :vat is ongeldig. Controleer het btw-nummer en probeer het opnieuw.',
            ],
        ],
    ],
];
