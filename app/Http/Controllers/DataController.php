<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Data;
use App\Transaction;
use App\Service;

use Ixudra\Curl\Facades\Curl;

class DataController extends Controller
{
    // buy airtime
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'network' => 'required',
            'phone_to' => 'required',
            'amount' => 'required',
            'description' => 'required',
            'transaction_id' => 'required|unique:transactions'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $request->request->add([
            'user_id' => $request->user()->id
        ]);
        $Data = Data::create($request->all());
        
        if($Data->save()) {
            $transaction = new Transaction;
            $transaction->user_id = $request->user_id;
            $transaction->service_id = Service::where('name', 'buy_data')->first()->id;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->save();
            return response()->json(['success' => 'added successfully']);
        }
    }

    // data has been paid for and verifed by paysatck
    public function paid($transaction_id) {
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->paid = true;
        $transaction->save();

        return response()->json(['success' => 'paid successfully']);
    }


    // call irecharge apis
    // public function get list on list
    public function check_data_bundles_list($param) {
        $url = 'https://irecharge.com.ng/pwr_api_live/v2/get_data_bundles.php?';

        $response = Curl::to($url.''.$param)
        ->get();
        return response($response);
    }

    // send data to user
    public function vend_data($param) {
        $url = 'https://irecharge.com.ng/pwr_api_live/v2/vend_data.php?';

        $response = Curl::to($url.''.$param)
        ->get();
        return response($response);
    }
}
