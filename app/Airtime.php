<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airtime extends Model
{
    protected $table = 'airtime';

    protected $fillable = [
        'user_id', 'network', 'phone_to', 'amount'
    ];
}
