<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Tv;
use App\Transaction;
use App\Service;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class TvController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'provider' => 'required',
            'package' => 'required',
            'amount' => 'required',
            'smart_card_no' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $request->merge([
            'user_id' => $request->user()->id
        ]);
        $tv = Tv::create($request->all());
        
        if($tv->save()) {
            $transaction = new Transaction;
            $transaction->user_id = $request->user_id;
            $transaction->service_id = Service::where('name', 'subscribe_tv')->first()->id;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->save();
            return response()->json(['success' => 'added successfully']);
        }
    }

    // tv has been paid for and verifed by paysatck
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

    // transaction history
    public function transactions(Request $request) {
        $transactions = Tv::where('user_id', $request->user()->id)
                        ->orderBy('created_at', 'DESC')
                        ->simplePaginate(5);

        return response()->json(['transactions' => $transactions]);
    }

    
    // IRECHARGE APIS ------------------------------------------------
    // check_tv_bundles_list
    public function check_tv_bundles_list($param) {
        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/get_tv_bouquet.php?';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/get_tv_bouquet.php?';

        $response = Curl::to($url.''.$param)
        ->get();
        
        return response($response);
    }

    // check tv card info
    public function check_tv_card($param) {
        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/get_smartcard_info.php?';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/get_smartcard_info.php?';

        $response = Curl::to($url.''.$param)
        ->get();

        return response($response);
    }

    // subscribe the tv
    public function vend_tv(Request $request, $param) {
        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/vend_tv.php?';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/vend_tv.php?';

        $response = Curl::to($url.''.$param)
        ->get();

        $data = [
            'message' => 'Your Tv was successfully Subscribed, Thank you for choosing Donlisa'
        ];
        Mail::to($request->user()->email)->send(new SendMailable($data));
        return response($response);
    }
}
