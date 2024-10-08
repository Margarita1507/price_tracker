<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'email'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
