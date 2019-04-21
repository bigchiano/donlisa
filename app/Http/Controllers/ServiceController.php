<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // generate hash for transaction
    public function getHash(Request $request, $combined_string) {
        $private_key = '5a52a78624f90ea38c277a7164458d91b15dcd494cc3fdd5508f4523e818ab325e91ba15b0ef485bb7fa62d699a7b4f409cf963574ecac8632149826277d8960';
        return hash_hmac("sha1", $combined_string, $private_key);
    }
}
