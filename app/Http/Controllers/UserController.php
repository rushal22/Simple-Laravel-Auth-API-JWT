<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Mail\ResetPasswordMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    public function forgetPassword(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'email'=> 'required|email|exists:users,email'
            ]);
            if($validator->fails()){
                return response()->json(['message'=>$validator->errors()], 401);
            }

            $token = Str::random(50);
            $domain = URL::to('http://localhost:3000');
            $url = $domain.'/resetpassword/'.$token;
            $datetime = Carbon::now();
            
            Mail::to($request->email)->send(new ResetPasswordMail($url));

            PasswordReset::updateOrCreate(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => $datetime
                ]
            );

            return response()->json(['message'=>'Password reset link send successfully.'], 200);
        
        }catch(\Exception $e){
            return response()->json(['message'=>'Link has been sent to your email!', 'error'=>$e->getMessage()], 402);
        }
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(),[
            // 'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        if($validator->fails()){
            return response()->json(['message'=>$validator->errors()], 401);
        }
                                // where('email',$request->email)
        $reset = PasswordReset::where('token', $request->token)
                            ->first();
        $email = $reset->email;
        if(!$reset){
            return response()->json(['message'=>'invalid token'], 401);
        }

        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // $reset->delete();
        PasswordReset::where('email', $email)->delete();

        return response()->json(['message'=>'Password Reset Successful!'], 200);


    }

    public function editprofile(Request $request){
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'firstName'=>'sometimes|required|max:30',
            'lastName'=>'sometimes|required|max:30',
            'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact' => 'sometimes|required|digits_between:10,15',
            'city' => 'sometimes|required|max:255',
            'address' => 'sometimes|required|max:255',
        ]);

        if($validator->fails()){
            return response()->json(['message'=>'error occured', 'error'=>$validator->errors()], 400);
        }

        try{
            $user = $request->user();
            if(isset($data['firstName'])){
                $user->firstName = $data['firstName'];
            }
            if(isset($data['lastName'])){
                $user->lastName = $data['lastName'];
            }
            $user->save();

            $userDetail = $user->userDetail ?? new UserDetail(['user_id' => $user->id]);

            if ($request->has('contact')) {
                $userDetail->contact = $data['contact'];
            }
            if ($request->has('city')) {
                $userDetail->city = $data['city'];
            }
            if ($request->has('address')) {
                $userDetail->address = $data['address'];
            }
            if($request->hasFile('profile_image')){

                $imagepath = $request->file('profile_image')->store('users', 'public');

                $imageURL = Storage::url($imagepath);
                $userDetail->profile_image = $imageURL;
            }

            $userDetail->save();
            $user->load('userDetail');
            return response()->json(['message'=>'Profile updated!', 'data'=>auth()->user()], 200);
        }catch(\Exception $e){
            return response()->json(['message'=>$e->getMessage()], 401);
        }
    }
}
