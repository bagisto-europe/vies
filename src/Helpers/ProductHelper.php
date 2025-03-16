<?php

namespace Bagisto\Vies\Helpers;

class ProductHelper
{
    /**
     * Get product types formatted for config options.
     */
    public static function getProductTypes(): array
    {
        $productTypes = config('product_types', []);

        $options = [];

        foreach ($productTypes as $key => $type) {
            $options[] = [
                'title' => $type['name'],
                'value' => $key,
            ];
        }

        return $options;
    }
}
