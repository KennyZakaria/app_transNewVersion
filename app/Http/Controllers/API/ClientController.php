<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
class ClientController  extends BaseController
{
    public function register(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'langKey' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
            'password' => 'required|string|min:8|max:255', 
            'c_password' => 'required|same:password',    
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),422);       
        }
        try {
            // Begin a database transaction
            DB::beginTransaction();
        
            $user = User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'langKey' => $request->input('langKey'), 
                'password' =>bcrypt($request->input('password')),
            ]);
           
            $role = Role::where('name', 'ROLE_CLIENT')->first();
            if ($role) {
                $user->roles()->attach($role);
            }

            $success['token'] =  $user->createToken('MyApp')->accessToken;
             
            $Client = Client::create([ 
                'user_id' => $user->id,
                'id' => $user->id,
                'tel' => $request->input('tel')
                
            ]); 
            DB::commit(); 
            return response()->json(['message' => 'Transporteur created successfully', 'data' => $Client], 201);
        } catch (\Exception $e) { 
            DB::rollback();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        } 
        return response()->json(['message' => 'Transporteur created successfully', 'data' => $Client], 201);
    }
     
}
