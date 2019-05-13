<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = 'data';

    protected $fillable = [
        'user_id', 'network', 'phone_to', 'description', 'amount',
        'transaction_id'
    ];
}
