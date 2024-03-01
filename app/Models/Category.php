<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function discounts() 
    {
        return $this->belongsToMany(Discount::class);
    }

    public function customerDiscounts()
    {
        return $this->belongsToMany(CustomerDiscount::class);
    }
}
