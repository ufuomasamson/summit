<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card_record extends Model
{
    use HasFactory;
    protected $fillable = [
        'user', 'amount','reference','type'
     ];
}
