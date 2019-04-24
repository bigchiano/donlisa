<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Power;
use App\Service;
use App\Transaction;

use Ixudra\Curl\Facades\Curl;

class PowerController extends Controller
{
    // buy power
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'meter_number' => 'required',
            'amount' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $request->request->add([
            'user_id' => $request->user()->id
        ]);
        $Power = Power::create($request->all());
        
        if($Power->save()) {
            $transaction = new Transaction;
            $transaction->user_id = $request->user_id;
            $transaction->service_id = Service::where('name', 'buy_power')->get()->id;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->save();
            return response()->json(['success' => 'added successfully']);
        }
    }

    // power has been paid for and verifed by paysatck
    public function paid($transaction_id) {
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->paid = true;
        $transaction->save();

        return response()->json(['success' => 'paid successfully']);
    }


    // make api calls to irecharge
    // get list of utilities
    public function list_utilities() {
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/get_electric_disco.php?response_format=json';

        $response = Curl::to($url)
        ->get();
        return response($response);
    }

    //get meter info
    public function get_meter_info($param) {
        $url = 'https://irecharge.com.ng/pwr_api_live/v2/get_meter_info.php?';

        $response = Curl::to($url.''.$param)
        ->get();
        return response($response);
    }

    // vend power
    public function vend_power($param) {
        $url = 'https://irecharge.com.ng/pwr_api_live/v2/vend_power.php?';

        $response = Curl::to($url.''.$param)
        ->get();
        return response($response);

    }
}
