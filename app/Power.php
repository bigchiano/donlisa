<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Power extends Model
{
    protected $table = 'power';

    protected $fillable = [
        'user_id', 'meter_number', 'amount', 'meter_token', 'name', 'units', 'address',
        'transaction_id', 'disco'
    ];
}
