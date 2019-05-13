<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Airtime;
use App\Transaction;
use App\Service;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class AirtimeController extends Controller
{
    // buy airtime
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'network' => 'required',
            'phone_to' => 'required',
            'amount' => 'required',
            // 'transaction_id' => 'required|unique:transactions'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $request->merge([
            'user_id' => $request->user()->id,
            'transaction_id' => $request->transaction_id
        ]);
        $Airtime = Airtime::create($request->all());

        if ($Airtime->save()) {
            $transaction = new Transaction;
            $transaction->user_id = $request->user_id;
            $transaction->service_id = Service::where('name', 'buy_airtime')->first()->id;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->save();
            return response()->json(['success' => 'added successfully']);
        }
    }

    // airtime has been paid for and verifed by paysatck
    public function paid($transaction_id)
    {
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->paid = true;
        $transaction->save();

        return response()->json(['success' => 'paid successfully']);
    }

    // transaction completed
    public function vend(Request $request, $transaction_id)
    {
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->delivered = true;
        $transaction->save();

        $data = [
            'message' => 'Your Sim Topup was successfull, Thank you for choosing Donlisa'
        ];
        Mail::to($request->user()->email)->send(new SendMailable($data));
        return response()->json(['success' => 'vend successfully']);
    }

    // transaction history
    public function transactions(Request $request)
    {
        $transactions = Airtime::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(5);

        return response()->json(['transactions' => $transactions]);
    }


    // IRECHARGE APIS ------------------------------------------------
    // call irecharge api to vend real airtime
    public function vend_airtime(Request $request, $param)
    {
        // $url = 'https://irecharge.com.ng/pwr_api_live/v2/vend_airtime.php?';
        // test url
        $url = 'https://irecharge.com.ng/pwr_api_sandbox/v2/vend_airtime.php?';
        
        $client = new Client([
            'timeout' => 100,
        ]);
        
        $response = $client->request('POST', $url.''.$param);

        return response($response->getBody()->getContents());
    }
}
