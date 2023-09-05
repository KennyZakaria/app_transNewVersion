<?php

namespace App\Http\Controllers\API;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
class VehiculeController extends BaseController
{
    public function store(Request $request)
    {
        if (auth()->check()) {
            try {
                $user = auth()->user(); // Get the authenticated user

                $validator = Validator::make($request->all(), [
                    'Marque' => 'required|string',
                    'Model' => 'required|string',
                    'Type' => 'required|string',
                    'Add_on' => 'nullable|string',
                    'Description' => 'nullable|string',
                    'Picture' => 'nullable|string',  
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(),400);
                }

                $validatedData = $validator->validated(); 
                $validatedData['transporteur_id'] = $user->id;
                $vehicule = Vehicule::create($validatedData);
                return $this->sendResponse($vehicule, 'Vehicule created successfully.');
             } catch (\Exception $e) {
                return $this->sendError('An error occurred.', $validator->errors(),400);
             }
        } else {
            return $this->sendError('authenticated.', 'User not authenticated',401); 
        } 
    }
    public function update(Request $request, $id)
    {
        if (auth()->check()) {
            try {
                $user = auth()->user(); // Get the authenticated user

                $vehicule = Vehicule::find($id);
                
                if (!$vehicule) {
                    return $this->sendError('not found error.', 'Vehicle not found',400); 
                }

                if ($vehicule->transporteur_id !== $user->id) {
                    return $this->sendError('Unauthorized.', 'Unauthorized',403); 
                }

                $validator = Validator::make($request->all(), [
                    'Marque' => 'required|string',
                    'Model' => 'required|string',
                    'Type' => 'required|string',
                    'Add_on' => 'nullable|string',
                    'Description' => 'nullable|string',
                    'Picture' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors(),400);
                }

                $vehicule->update($request->all());
                return $this->sendResponse($vehicule, 'Vehicule updated successfully.');
             } catch (\Exception $e) {
                return $this->sendError('An error occurred.', $e->getMessage(),500); 
             }
        } else {
            return $this->sendError('authenticated.', 'User not authenticated',401); 
        }
    }
    public function destroy($id)
    {
        if (auth()->check()) {
            try {
                $user = auth()->user(); 
                $vehicule = Vehicule::find($id);

                if (!$vehicule) {
                    return $this->sendError('not found error.', 'Vehicle not found',400); 
                }
                if ($vehicule->transporteur_id !== $user->id) {
                    return $this->sendError('Unauthorized.', 'Unauthorized',403); 
                }
                $vehicule->delete();
                return $this->sendResponse($vehicule, 'Vehicule deleted successfully.'); 
            } catch (\Exception $e) {
                return $this->sendError('An error occurred.', $e->getMessage(),500); 
            }
        } else {
            return $this->sendError('authenticated.', 'User not authenticated',401); 
        }
    }
    public function show($id)
    {
        if (auth()->check()) {
            try {
                $user = auth()->user(); // Get the authenticated user

                $vehicule = Vehicule::find($id);  
                if (!$vehicule) {
                    return $this->sendError('not found error.', 'Vehicle not found',400); 
                }
                if ($vehicule->transporteur_id !== $user->id) {
                    return $this->sendError('Unauthorized.', 'Unauthorized',403); 
                }
                return $this->sendResponse($vehicule, 'Vehicle found.'); 
            } catch (\Exception $e) {
                return $this->sendError('An error occurred.', $e->getMessage(),500); 
            }
        } else {
            return $this->sendError('authenticated.', 'User not authenticated',401); 
        }
    }
    public function index()
    {
        if (auth()->check()) {
            try {
                $user = auth()->user();
                $vehicules = Vehicule::where('transporteur_id', $user->id)->get();
                return $this->sendResponse($vehicules, 'list Vehicle .'); 
            } catch (\Exception $e) {
                return $this->sendError('An error occurred.', $e->getMessage(),500); 
            }
        } else {
            return $this->sendError('authenticated.', 'User not authenticated',401); 
        }
    }
}
