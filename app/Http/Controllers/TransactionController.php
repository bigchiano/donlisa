<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $data = [];
        $data['power'] = $user->power;
        $data['data'] = $user->data;
        $data['tv'] = $user->tv;
        $data['airtime'] = $user->airtime;

        $total = [
            'power' => 0,
            'data' => 0,
            'tv' => 0,
            'airtime' => 0
        ];
        foreach ($data['power'] as $key) {
            $total['power'] += (float) $key['amount'];
        }
        foreach ($data['data'] as $key) {
            $total['data'] += (float) $key['amount'];
        }
        foreach ($data['tv'] as $key) {
            $total['tv'] += (float) $key['amount'];
        }
        foreach ($data['airtime'] as $key) {
            $total['airtime'] += (float) $key['amount'];
        }
        return response()->json($total);
    }
}
