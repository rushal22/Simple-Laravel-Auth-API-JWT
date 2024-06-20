<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ManagementController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'firstName', 'lastName', 'email', 'role')->get();
        return response()->json(['data'=>$users]);
    }

    public function update(Request $request, string $id)
    {
        $validatedData = Validator::make($request->all(),[
            'firstName'=>'required',
            'lastName'=>'required',
            'email'=>'required',
            'role' => 'required|in:admin,user',
        ]);
        if($validatedData->fails())
        {
            return response()->json(['error'=>$validatedData->errors()]);
        }
        try{
            $user = User::findorFail($id);
            $user->role = $request->role;
            $user->save();
            return response()->json(['message'=>'User updated successfully!', 'updated'=> $user], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred!', 'errors' => $e->getMessage()], 400);
        }
    }
}
