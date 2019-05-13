<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tv extends Model
{
    protected $table = 'Tv';

    protected $fillable = [ 
        'user_id', 'provider', 'package', 'amount', 'smart_card_no',
        'transaction_id'
    ];
}
