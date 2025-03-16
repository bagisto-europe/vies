<?php

return [
    'admin' => [
        'sales' => [
            'taxes' => [
                'vies' => [
                    'validation'                 => 'Validazione VIES',
                    'validation_info'            => 'Abilita o disabilita la validazione VIES per i numeri di partita IVA dell\'UE per garantire la validità dei numeri di partita IVA all\'interno dell\'UE.',
                    'status'                     => 'Stato',
                    'status_info'                => 'Abilita o disabilita la validazione VIES.',
                    'update_company_name'        => 'Aggiorna il nome dell\'azienda',
                    'update_company_name_info'   => 'Se abilitato, il nome dell\'azienda del cliente verrà aggiornato in base alla risposta VIES.',
                    'exclude_product_types'      => 'Escludi i tipi di prodotto dalla Reverse Charge',
                    'exclude_product_types_info' => 'Specifica quali tipi di prodotto devono essere esclusi dalla Reverse Charge IVA.',
                ],
            ],
        ],
    ],
    'customers' => [
        'account' => [
            'addresses' => [
                'invalid-vat' => 'Il numero di partita IVA fornito :vat non è valido. Controlla il numero di partita IVA e riprova.',
            ],
        ],
    ],
];
