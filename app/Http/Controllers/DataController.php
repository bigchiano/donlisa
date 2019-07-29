<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Data;
use App\Transaction;
use App\Service;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

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

        $request->merge([
            'user_id' => $request->user()->id,
            'transaction_id' => $request->transaction_id
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

    // transaction completed
    public function vend($transaction_id) {
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->delivered = true;
        $transaction->save();

        return response()->json(['success' => 'sent successfully']);
    }

    // transaction history
    public function transactions(Request $request) {
        $transactions = Data::where('user_id', $request->user()->id)
                        ->orderBy('created_at', 'DESC')
                        ->simplePaginate(5);

        return response()->json(['transactions' => $transactions]);
    }

    // IRECHARGE APIS ------------------------------------------------
    // public function get list on list
    public function check_data_bundles_list($param) {
        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/get_data_bundles.php?';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/get_data_bundles.php?';

        $response = Curl::to($url.''.$param)
        ->get();
        return response($response);
    }

    // send data to user
    public function vend_data(Request $request, $param) {
        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/vend_data.php?';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/vend_data.php?';

        $response = Curl::to($url.''.$param)
        ->get();
        
        $data = [
            'message' => 'Your Data Subscription was successfull, Thank you for choosing Donlisa'
        ];
        Mail::to($request->user()->email)->send(new SendMailable($data));
        return response($response);
    }
}
