<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            return response()->json(['message'=>'successfull', 'data'=>$user], 200);
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
            return response()->json(['message'=>'error occurred'],400);
        }
        else{
            if(Auth::attempt($data)){
                // $loginEmail = [
                //         'email'=>$data['email'],
                //         'password'=>$data['password']
                //     ];
                $token = Auth::attempt($data);
                
                // $response = $this->respondWithToken($token);
                return response()->json(['message'=>'successfull', 'token'=>$token],200);
            }
            else{
                return response()->json(['message'=>'invalid credentials'], 400);
            }
            // $loginEmail = [
                //     'email'=>$data['email'],
                //     'password'=>$data['password']
                // ];

            // $loggedin = false;
            // $token = '';
            // if($log2 = Auth::attempt($loginEmail)){
            //     $loggedin = true;
            //     $token = $log2;
            // }

            // if($loggedin == false){
            //     return response()->json(['message'=>'invalid'], 400);
            // }

            // $response = $this->respondWithToken($token);
            // return response()->json(['token'=>$response],200);

            
        }
    }

    // public function respondWithToken($token){
    //     return response()->json([
    //         'message'=>'logged in successfully', 
    //         'token_type'=>'Bearer', 
    //         'access_token'=>$token
    //     ]);
    // }

    public function profile(Request $request){
        return response()->json(auth()->user());
    }

    public function logout(){
        $log = Auth::guard('api')->logout();

        if($log){
            
            return response()->json(['message'=>'logged out successfully'], 200);
        }
        else{
            return response()->json(['message'=>'failed'], 400);
        }
    }
}
