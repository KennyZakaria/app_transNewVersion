<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transporteur;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\TransporteurCategorie;
use App\Models\Categorie; 
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class TransporteurController  extends BaseController
{
     
    public function index()
    {
        $transporteurs = Transporteur::all();
        return response()->json($transporteurs);
    } 

    public function updateTransporteur(Request $request)
    {
        $user_id=$request->user()->id;
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Transport company,Auto entrepreneur,Private carrier',
            'CinRectoURU' => 'required',
            'CinVersoURU' => 'required',
            'VehicleURUS' => 'required', 
            'ville_id' => 'required|exists:villes,id',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'unique:users,email,'.$user_id,
            'langKey' => 'required|string|max:255',
            'imageUrl' => 'url|max:255',  
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Begin a database transaction
            DB::beginTransaction(); 
            $user = User::find($user_id); // Retrieve the user you want to update
           
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $dataToUpdate = [
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'langKey' => $request->input('langKey'),
                'imageUrl' => $request->input('imageUrl'),
            ];
     
            if ($user->email !== $request->input('email')) {
                $dataToUpdate['email'] = $request->input('email');
                $dataToUpdate['verifiedEmail'] = false;  
                $dataToUpdate['email_verified_at'] = null;  
            }
    
            $user->update($dataToUpdate);
            $transporteur = Transporteur::where('user_id', $user_id)->first();  

            if (!$transporteur) {
                return response()->json(['error' => 'Transporteur not found'], 404);
            }

            Transporteur::where('user_id', $user_id)->update([
                'status' => $request->input('status'),
                'CinRectoURU' => $request->input('CinRectoURU'),
                'CinVersoURU' => $request->input('CinVersoURU'),
                'VehicleURUS' => $request->input('VehicleURUS'),
                'ville_id' => $request->input('ville_id'),
                'pieceJoindreByType' => $request->input('pieceJoindreByType'), 
            ]);
 
            DB::commit();
            $transporteur = Transporteur::where('user_id', $user_id)->first();  
            $updatedCategorieIds = $request->input('categorie_ids');
     
            $updatedCategories = Categorie::whereIn('id', $updatedCategorieIds)->get();
             
            $transporteur->services()->sync($updatedCategories);

            return response()->json(['message' => 'Transporteur updated successfully', 'data' => $transporteur], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
    public function getTransporteurDetails(Request $request)
    {  if (auth()->check()) {
        try {   
            $user_id=$request->user()->id;
            $transporteur = Transporteur::with('ville','user','vehicules.photos','categories')->where('user_id', $user_id)->first(); 
            if (!$transporteur) {
                return response()->json(['error' => 'Transporteur not found'], 404);
            } 
           
            return response()->json(['message' => 'Transporteur details retrieved successfully', 'data' => $transporteur ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    } else {
        return response()->json(['message' => 'This action is unauthorized'], 401);
    }
       
    }
    
}