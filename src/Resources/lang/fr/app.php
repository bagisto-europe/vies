<?php

return [
    'admin' => [
        'sales' => [
            'taxes' => [
                'vies' => [
                    'validation'                 => 'Validation VIES',
                    'validation_info'            => 'Activez ou désactivez la validation VIES pour les numéros de TVA de l\'UE afin d\'assurer la validité des numéros de TVA au sein de l\'UE.',
                    'status'                     => 'Statut',
                    'status_info'                => 'Activez ou désactivez la validation VIES.',
                    'update_company_name'        => 'Mettre à jour le nom de l\'entreprise',
                    'update_company_name_info'   => 'Si activé, le nom de l\'entreprise du client sera mis à jour en fonction de la réponse VIES.',
                    'exclude_product_types'      => 'Exclure les types de produits de la TVA sur l\'auto-liquidation',
                    'exclude_product_types_info' => 'Spécifiez les types de produits à exclure de la TVA sur l\'auto-liquidation.',
                ],
            ],
        ],
    ],
    'customers' => [
        'account' => [
            'addresses' => [
                'invalid-vat' => 'Le numéro de TVA fourni :vat est invalide. Veuillez vérifier le numéro de TVA et réessayer.',
            ],
        ],
    ],
];
