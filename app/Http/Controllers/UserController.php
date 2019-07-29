<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

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

    // send password reset link
    public function send(Request $request)
    {
        $email = $request->email;
        if(!isset($email)) {
            return response()->json(['error' => 'Email is required']);
        }

        $user = User::where('email', $email)->first();
        if($user->count() < 1) {
            return response()->json(['error' => 'Invalid email address']);
        }

        $msg = $user->name. 'You have request to reset your password,
               CLick this link to reset your password'; 

        $data = [
            'message' => $msg
        ];
        // Mail::to($user->email)->send(new SendMailable($data));

        return response()->json(['success' => 'Mail has been sent successfully']);
    }

}
