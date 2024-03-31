<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['prod_name', 'prod_img', 'unicode', 'price', 'stock', 'sku', 'is_deleted'];
    
    public function sales()
    {
        return $this->hasMany(Sales_details::class);
    }
}
