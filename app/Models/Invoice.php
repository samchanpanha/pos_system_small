<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
          'total',
          'receive_usd',
          'receive_riel',
          'total_profit',
          'user_id'
    ];
}
