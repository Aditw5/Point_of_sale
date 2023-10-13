<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;
    

    public function produk()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
