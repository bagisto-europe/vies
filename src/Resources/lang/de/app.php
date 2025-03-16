<?php

return [
    'admin' => [
        'sales' => [
            'taxes' => [
                'vies' => [
                    'validation'                 => 'VIES Validierung',
                    'validation_info'            => 'Aktivieren oder deaktivieren Sie die VIES-Validierung für EU-USt-IdNrn., um die Gültigkeit von USt-IdNrn. innerhalb der EU sicherzustellen.',
                    'status'                     => 'Status',
                    'status_info'                => 'Aktivieren oder deaktivieren Sie die VIES-Validierung.',
                    'update_company_name'        => 'Firma aktualisieren',
                    'update_company_name_info'   => 'Wenn aktiviert, wird der Firmenname des Kunden basierend auf der VIES-Antwort aktualisiert.',
                    'exclude_product_types'      => 'Produkten aus Reverse Charge ausnehmen',
                    'exclude_product_types_info' => 'Geben Sie an, welche Produkttypen von der Reverse-Charge-USt. ausgeschlossen werden sollen.',
                ],
            ],
        ],
    ],
    'customers' => [
        'account' => [
            'addresses' => [
                'invalid-vat' => 'Die angegebene USt-IdNr. :vat ist ungültig. Bitte überprüfen Sie die USt-IdNr. und versuchen Sie es erneut.',
            ],
        ],
    ],
];
