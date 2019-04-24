<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class UserController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50', 
            'email' => 'required|max:50|email|unique:users',
            'phone' => 'required|max:20|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) { 
            return response()->json(['errors' => $validator->errors()]);            
        }
        $input = $request->all(); 
        $request->merge([
            'password' => bcrypt($input['password'])
        ]);
        $user = User::create($request->all()); 
        $token =  $user->createToken('MyApp')->accessToken;
        return response()->json(['token' => $token]); 
    }

    //login user
    public function login(Request $request){ 
        $credentials = $request->only('email', 'password');
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])
            || Auth::attempt(['phone' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $token =  $user->createToken('MyApp')->accessToken; 
            return response()->json(['token' => $token]); 
        } else { 
            return response()->json(['error' => 'Invalid email or password']);
        }         
    }

}
