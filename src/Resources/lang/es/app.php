<?php

return [
    'admin' => [
        'sales' => [
            'taxes' => [
                'vies' => [
                    'validation'                 => 'Validación VIES',
                    'validation_info'            => 'Habilite o deshabilite la validación VIES para los números de IVA de la UE para garantizar la validez de los números de IVA dentro de la UE.',
                    'status'                     => 'Estado',
                    'status_info'                => 'Habilite o deshabilite la validación VIES.',
                    'update_company_name'        => 'Actualizar nombre de la empresa',
                    'update_company_name_info'   => 'Si está habilitado, el nombre de la empresa del cliente se actualizará en función de la respuesta VIES.',
                    'exclude_product_types'      => 'Excluir tipos de productos de Reverse Charge',
                    'exclude_product_types_info' => 'Especifique qué tipos de productos deben ser excluidos de la Reverse Charge IVA.',
                ],
            ],
        ],
    ],
    'customers' => [
        'account' => [
            'addresses' => [
                'invalid-vat' => 'El número de IVA proporcionado :vat no es válido. Verifique el número de IVA e intente nuevamente.',
            ],
        ],
    ],
];
