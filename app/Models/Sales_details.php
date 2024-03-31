<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales_details extends Model
{
    use HasFactory;
    
    protected $fillable = ['sales_id', 'product_id', 'quantity', 'subtotal'];

    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
