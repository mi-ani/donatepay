<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XyzPaymentService extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sum',
        'name'
    ];
}
