<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class TransactionController extends Controller
{

    // mark transaction as paid for airtime
    public function paid_airtime($transaction_id) {
        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $transaction->paid = true;
        $transaction->save();
        return response()->json('success');
    }
}
