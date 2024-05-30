<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    
    /**
     * Handle the Product "saving" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */

    public function saving(Product $product)
    {
        $product->selling_price = ($product->buying_price - ($product->buying_price * $product->discount / 100)) * (1 + $product->margin / 100);
    }

}
