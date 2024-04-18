<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $fillable = ['cust_name', 'cust_address', 'cust_phone', 'is_deleted'];

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
