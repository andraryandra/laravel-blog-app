<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //register use
    public function register(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        //create user
        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password'])
        ]);

        // create users & token in response
        return response([
            'user' => $user,
            'token' => $user->createToken('Create Token')->plainTextToken
        ]);
    }

    // public function login(Request $request)
    // {
    //     $attrs = $request->validate([
    //         // 'role' => 'required',
    //         'email' => 'required',
    //         'password' => 'required|string',
    //     ]);

    //     if(!Auth::attempt($attrs)) {
    //         return response()->json([
    //             'message' => 'Incorrect role or email or password'
    //         ], 403);
    //     }

    //     //return user & token in response
    //     return response([
    //         'user' => $request->auth()->user(),
    //         'token' => $request->auth()->user()->createToken('secret')->plainTextToken
    //     ], 200);
    // }

    
    public function logout(User $user){

        $user->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    // ge user details
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }

    // update user details
    public function update(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string',
        ]);

        $image = $this->saveImage($request->image, 'profiles');

        $request->auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $image
        ]);

        return response([
            'message' => 'user updated.',
            'user' => auth()->user()
        ], 200);
    }

    // login user
    public function login(Request $request, User $user)
    {
        // Validate Inputs
        $attrs = [
            // 'role' => 'required',
            'email' => 'required',
            'password' => 'required|string',
        ];
        $request->validate($attrs);
        // find user email and role in users table
        $user = User::where('email', $request->email)->first();
        // if user email found and password is correct
        if($user && Hash::check($request->password, $user->password)){
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            $response=['user'=> $user, 'token'=> $token];
            return response()->json($response, 200);
        }
        $response = ['message'=>'Incorrect role or email or password'];
        return response()->json($response, 400);
    }

    

}
