<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserController extends Controller
{
    public function register(Request $request){
        $data = $request->all();
        $validator = Validator::make($request->all(),[
            'firstName'=>'required|max:50',
            'lastName'=>'required|max:50',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8'
        ]);
        if($validator->fails()){
            
            return response()->json(['message'=>'error occurred', 'error'=> $validator->errors()], 400);
        }
        else{
            $user = new User;
            $user->firstName = $data['firstName'];
            $user->lastName = $data['lastName'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->save();
            return response()->json(['message'=>'User Registered!', 'data'=>$user], 200);
        }
    }

    public function login(Request $request){
        // $data = $request->all();
        $data = $request->only('email', 'password');

        $validator = Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required'
        ]);
        
        if($validator->fails()){
            return response()->json(['message'=>'error occurred', 'error'=>$validator->errors()],400);
        }
        else{
            if(JWTAuth::attempt($data)){
                $token = JWTAuth::attempt($data);
                return response()->json(['message'=>'Login Successfull', 'access_token'=>$token, 'token_type'=>'Bearer', 'user_data'=>auth()->user()],200);
            }
            else{
                return response()->json(['message'=>'Invalid email or password'], 400);
            }
        }
    }

    public function profile(Request $request){
        return response()->json(auth()->user(), 200);
    }

    public function logout(){
        try {
            // Invalidate the token by adding it to the blacklist
            JWTAuth::parseToken()->invalidate();
            
            // Optionally, clear the token from the client-side storage (e.g., browser localStorage)
            // This step is recommended for better user experience
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            // Something went wrong while invalidating the token
            return response()->json(['message' => 'Failed to logout', 'error'=>$e], 500);
        }
    }

    public function refreshToken(){
        $token = JWTAuth::getToken();

        if($token){
            try{
                $newToken = JWTAuth::refresh($token);
                return response()->json(['access_token'=>$newToken, 'token_type'=>'Bearer'], 201);
            }
            catch(TokenInvalidException $e){
                return response()->json(['error'=>$e], 401);
            }
        }
    }
}
