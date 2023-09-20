<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\Client;
use App\Models\Categorie;
use App\Models\Role;
use App\Models\Transporteur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
     
class RegisterController extends BaseController
{
     
    public function clientLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email',
            'password' => 'required', 
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user(); 

            if ($user->hasRole('role_client')) {
                $success['token'] = $user->createToken('ClientApp')->accessToken; 

                return $this->sendResponse($success, 'Client login successfully.');
            }
        }
        return $this->sendError('Unauthorised.', ['error'=>'Client not found'],404);
    }
    public function transporteurLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email',
            'password' => 'required', 
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user(); 

            if ($user->hasRole('role_transporteur')) {
                $success['token'] = $user->createToken('TransporteurApp')->accessToken;
                $success['name'] = $user->name;

                return $this->sendResponse($success, 'Transporteur login successfully.');
            }
        }

        return $this->sendError('Unauthorised.', ['error'=>'Transporteur not found'],404);
    }
    public function logout(Request $request)
    { 
        $request->user()->token()->revoke();
        $success=null;
        return $this->sendResponse($success, 'User Successfully logged out.');
    }
    public function emailExists(Request $request) {
        $resp = User::where('email', $request->email)->exists();
        return $this->sendResponse($resp, 'emailExists');
    }
    public function clientRegister(Request $request)
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
    public function transporteurRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Transport company,Auto entrepreneur,Private carrier',
            'CinRectoURU' => 'required',
            'CinVersoURU' => 'required',
            'VehicleURUS' => 'required',
            'piecejoindre' => 'required', 
            'ville_id' => 'required|exists:villes,id',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'langKey' => 'required|string|max:255',
            'imageUrl' => 'url|max:255',
            'password' => 'required|string|min:8|max:255', // Adjust the min and max length as per your requirement
            'c_password' => 'required|same:password',   
             'categories_ids' => 'required|array',
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
                'imageUrl' => $request->input('imageUrl'),
                'password' =>bcrypt($request->input('password')),
            ]);
           
            $role = Role::where('name', 'ROLE_TRANSPORTEUR')->first();
            if ($role) {
                $user->roles()->attach($role);
            }

            $success['token'] =  $user->createToken('AppTransporteur')->accessToken;
             
            $transporteur = Transporteur::create([
                'status' => $request->input('status'),
                'CinRectoURU' => $request->input('CinRectoURU'),
                'CinVersoURU' => $request->input('CinVersoURU'),
                'VehicleURUS' => $request->input('VehicleURUS'),
                'user_id' => $user->id,
                'id' => $user->id,
                'ville_id' => $request->input('ville_id'),
                'pieceJoindreByType'=>$request->input('pieceJoindreByType'), 
            ]);
            $categorieIds = $request->input('categories_ids'); // Corrected line
            $categories=null;
            if($categorieIds!=null){
                $categories = Categorie::whereIn('id', $categorieIds)->get();
            } 
            DB::commit();
            $transporteur->id=$transporteur->user_id; 
            $transporteur->categories()->attach($categories);
            return response()->json(['message' => 'Transporteur created successfully', 'data' => $transporteur], 201);
        } catch (\Exception $e) { 
            DB::rollback();
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Transporteur created successfully', 'data' => $transporteur], 201);
    }


}