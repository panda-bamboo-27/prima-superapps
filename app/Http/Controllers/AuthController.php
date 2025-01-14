<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    =>  $validator->errors()
            ], 422);
        }

        $user = User::where('email',$request->email)->first();

        if ($user == null) {
            return response()->json([
                'message'   => 'Credentials Invalid.',
                'errors'    =>  ['email'    => 'Email not registered.']
            ], 401);
        }

        if (Auth::attempt($validator->validated())) {
            $token = $user->createToken('sample login token');
            return response()->json(['isLoggedIn' => true, 'token' => $token]);
        }
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['isLoggedIn' => false, 'message' => 'Successfully log out.']);
    }
}
