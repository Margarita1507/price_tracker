<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['olx_url', 'price'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
