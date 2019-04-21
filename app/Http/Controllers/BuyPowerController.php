<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Power;

class BuyPowerController extends Controller
{
    public function save(Request $request) {
        $validator = Validator::make($request->all(), [
            'meter_number' => 'required',
            'amount' => 'required'
        ]);
    }
}
