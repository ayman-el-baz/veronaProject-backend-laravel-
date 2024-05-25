<?php

namespace App\Http\Controllers;

use App\Mail\ForgetPassword;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class UtilisateurController  extends Controller
{
  // Register Client
  public function Register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users',
      'password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }
    $user = new User();
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->password = bcrypt($request->input('password')); // You should hash the password before saving it

    // Save the user to the database
    $user->save();

    // Registration logic goes here

    return response()->json('Registration successful');
  }


  // Login Client
  public function Login(Request $req)
  {
    // Validate the request data
    $validatedData = $req->validate([
      'email' => 'required|email',
      'password' => 'required|string|min:8|max:255'
    ]);

    // Find the client by email
    $client = Client::where('email', $validatedData['email'])->first();

    // Check if client exists and password is correct
    if ($client && Hash::check($validatedData['password'], $client->password)) {
      // Generate a token
      $token = Str::random(40);

      // Return success response
      return response()->json([
        'message' => 'Successfully logged in',
        'data' => $client,
        'token' => $token
      ], 200);
    } else {
      // Log the failed login attempt
      Log::warning('Login attempt failed:', [
        'email' => $validatedData['email'],
        'message' => $client ? 'Password mismatch' : 'Client not found'
      ]);

      // Return error response
      return response()->json(['error' => 'Email or Password is not matched'], 401);
    }
  }

  // forget password 
  public function ForgetPassword(Request $request)
  {
    $Validation = $request->validate([
      'email' => 'required|email'
    ]);

    // $NumForgetPassword = Str::random(4, 'numeric');
    $code = mt_rand(100000, 999999);

    $Client = Client::where('email', $Validation['email'])->first();

    if (!$Client) {
      return response()->json(["error" => "this email has not exist"]);
    }

    $Client->forgetPassNum = $code;
    $Client->save();

    // sent Mail forget password 
    Mail::to($Validation['email'])->send(new ForgetPassword($code));


    return response()->json(["message" => "Success"]);
  }


  // Verify Code
  public function VerifyCode(Request $request)
  {
    $validatedData = $request->validate([
      'code' => 'required|min:6|max:6',
    ]);

    $code = Client::where('forgetPassNum', $validatedData['code'])->first();
    if ($code) {
      return ['message' => "Success"];
    } else {
      return ['error' => "invalid code !"];
    }
  }
}
