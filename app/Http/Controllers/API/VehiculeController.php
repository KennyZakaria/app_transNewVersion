<?php

namespace App\Http\Controllers\API;
use App\Models\Vehicule;
use App\Models\PhotoVehicule;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\Auth;
class VehiculeController extends BaseController
{
    public function index()
    {
        $vehicules = Vehicule::with('photos')
        ->where('transporteur_id', Auth::user()->id)
        ->get();
        return response()->json(['data' => $vehicules], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Description' => 'nullable|string',
            'Marque' => 'required|string',
            'Model' => 'required|string',
            'vehicle_types_id' => 'required|integer',
        ]);
         
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        try { 
            $vehicule = Vehicule::create([
                'Description' => $request->input('Description'),
                'Marque' => $request->input('Marque'),
                'Model' => $request->input('Model'),
                'transporteur_id' => Auth::user()->id,
                'vehicle_types_id' => $request->input('vehicle_types_id'),
            ]);
        
            if ($request->has('photos')) {
                $this->attachPhotos($vehicule, $request->input('photos'));
            }
         
            return response()->json(['message' => 'Vehicule created successfully', 'data' => $vehicule], 201);
         } catch (\Exception $e) { 
            return response()->json(['error' => 'Failed to create Vehicule'], 500);
        }
    }

    public function show($id)
    { 
        $vehicule = Vehicule::with('photos')->findOrFail($id); 
        if ($vehicule->transporteur_id !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } 
        return response()->json(['data' => $vehicule], 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'Description' => 'nullable|string',
            'Marque' => 'required|string',
            'Model' => 'required|string',
            'vehicle_types_id' => 'required|integer',
        ]);
        $vehicule = Vehicule::findOrFail($id);
        if ($vehicule->transporteur_id !== Auth::user()->id) {
            return $this->sendError('Unauthorized.', 'Unauthorized', 403);
        }

        $vehicule->update([
            'Description' => $request->input('Description'),
            'Marque' => $request->input('Marque'),
            'Model' => $request->input('Model'),
            'transporteur_id' => Auth::user()->id,
            'vehicle_types_id' => $request->input('vehicle_types_id'),
        ]);
        if ($request->has('photos')) {
            $this->syncPhotos($vehicule, $request->input('photos'));
        }
        return response()->json(['message' => 'Vehicule updated successfully', 'data' => $vehicule], 200);
    }

    public function destroy($id)
    {
        $vehicule = Vehicule::findOrFail($id);
        $vehicule->delete();
        return response()->json(['message' => 'Vehicule deleted successfully']);
    }

    private function attachPhotos(Vehicule $vehicule, $photos)
    {
        foreach ($photos as $photoData) {
            $photo = new PhotoVehicule($photoData);
            $vehicule->photos()->save($photo);
        }
    }
    private function syncPhotos(Vehicule $vehicule, $photos)
    {
        $vehicule->photos()->delete();
        $this->attachPhotos($vehicule, $photos);
    }
}
