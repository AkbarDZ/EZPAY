<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = ['sales_date', 'total_price', 'customer_id', 'user_id'];

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }

    public function details()
    {
        return $this->hasMany(Sales_details::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
