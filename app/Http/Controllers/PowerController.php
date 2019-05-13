<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Power;
use App\Service;
use App\Transaction;
use Validator;


use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use GuzzleHttp\Client;

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
        
        
        $request->merge([
            'user_id' => $request->user()->id
        ]);
        $power = Power::create($request->all());

        if($power->save()) {
            $transaction = new Transaction;
            $transaction->user_id = $request->user_id;
            $transaction->service_id = Service::where('name', 'buy_power')->first()->id;
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

    // transaction completed
    public function vend(Request $request, $transaction_id) {
        $power = Power::where('transaction_id', $transaction_id)->first();
        $power->meter_token = $request->meter_token;
        $power->units = $request->units;
        $power->save();

        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->delivered = true;
        $transaction->save();

        $msg = 'Your Power purchase was successfull, Thank you for choosing Donlisa'; 
        // $msg += 'Thank you for choosing Donlisa';
        $data = [
            'message' => $msg
        ];
        Mail::to($request->user()->email)->send(new SendMailable($data));
        return response()->json(['success' => 'vend successfully']);
    }


    // transaction history
    public function transactions(Request $request) {
        $transactions = Power::where('user_id', $request->user()->id)
                        ->orderBy('created_at', 'DESC')
                        ->simplePaginate(5);

        return response()->json(['transactions' => $transactions]);
    }

    // IRECHARGE APIS ------------------------------------------------
    // get list of utilities
    public function list_utilities() {
        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/get_electric_disco.php?response_format=json';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/get_electric_disco.php?response_format=json';

        $client = new Client([
            'timeout' => 100,
        ]);
        
        $response = $client->request('POST', $url);

        return response($response->getBody()->getContents());
    }

    //get meter info
    public function get_meter_info($param) {
        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/get_meter_info.php?';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/get_meter_info.php?';

        $client = new Client([
            'timeout' => 100,
        ]);
        
        $response = $client->request('POST', $url.''.$param);

        return response($response->getBody()->getContents());
    }

    // vend power
    public function vend_power(Request $request, $param) {
        set_time_limit(100);

        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/vend_power.php?';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/vend_power.php?';

        $client = new Client([
            'timeout' => 100,
        ]);
        
        $response = $client->request('POST', $url.''.$param);

        return response($response->getBody()->getContents());

    }
}
