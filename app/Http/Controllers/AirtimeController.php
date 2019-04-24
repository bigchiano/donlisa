<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Airtime;
use App\Transaction;
use App\Service;

use Ixudra\Curl\Facades\Curl;

class AirtimeController extends Controller
{
    // buy airtime
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'network' => 'required',
            'phone_to' => 'required',
            'amount' => 'required',
            'transaction_id' => 'required|unique:transactions'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $request->request->add([
            'user_id' => $request->user()->id
        ]);
        $Airtime = Airtime::create($request->all());
        
        if($Airtime->save()) {
            $transaction = new Transaction;
            $transaction->user_id = $request->user_id;
            $transaction->service_id = Service::where('name', 'buy_airtime')->first()->id;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->save();
            return response()->json(['success' => 'added successfully']);
        }
    }

    // airtime has been paid for and verifed by paysatck
    public function paid($transaction_id) {
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->paid = true;
        $transaction->save();

        return response()->json(['success' => 'paid successfully']);
    }

    // transaction completed
    public function vend($transaction_id) {
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->delivered = true;
        $transaction->save();

        return response()->json(['success' => 'vend successfully']);
    }

    // call irecharge api to vend real airtime
    public function vend_airtime(Request $request, $param) {
        $url = 'https://irecharge.com.ng/pwr_api_live/v2/vend_airtime.php?';

        $response = Curl::to($url.''.$param)
        ->get();
        return response($response);
    }
}
