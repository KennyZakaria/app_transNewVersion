<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Devi;
use App\Models\Offre;
use App\Models\AcceptAction;
use Validator;
use Carbon\Carbon;
class DeviController extends Controller
{
    public function addDevi(Request $request)
    {  
        $validator = Validator::make($request->all(), [ 
            'prix' => 'nullable|numeric',
            'offre_id' => 'required|exists:offres,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }
        $existingDevi = Devi::where('offre_id', $request->offre_id)
            ->where('transporteur_id', $request->user()->id)
            ->where('status', 'EnCours')
            ->first();

        if ($existingDevi) {
            return response()->json(['message' => 'Un devis existe déjà pour cette offre.'], 422);
        }

        $offre = Offre::find($request->offre_id);

        if (!$offre) {
            return response()->json(['message' => "L'offre n'existe pas."], 422);
        }

        Devi::create([
            'date' => Carbon::now(),
            'prix' => $request->input('prix'),
            'status' => 'EnCours',
            'offre_id' => $request->input('offre_id'),
            'transporteur_id' => $request->user()->id,
        ]);
        return response()->json(['message' => 'Devi added successfully.']);
    }
    public function updateDevi(Request $request, $deviId)
    {
        $validator = Validator::make($request->all(), [
            'prix' => 'nullable|numeric',
            'offre_id' => 'exists:offres,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        $devi = Devi::where('id', $deviId)
            ->where('transporteur_id', $request->user()->id)
            ->where('status', 'EnCours')
            ->first();

        if (!$devi) {
            return response()->json(['message' => "Devi not found or unauthorized."], 404);
        }

        // Update Devi fields based on request input
        if ($request->has('prix')) {
            $devi->prix = $request->input('prix');
        }
        
        if ($request->has('offre_id')) {
            $offre = Offre::find($request->input('offre_id'));
            if (!$offre) {
                return response()->json(['message' => "The specified offre does not exist."], 422);
            }
            $devi->offre_id = $request->input('offre_id');
        }

        $devi->save();

        return response()->json(['message' => 'Devi updated successfully.']);
    }
    public function acceptDevi(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'deviId' => 'required|exists:devis,id',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }
        $deviId = $request->input('deviId');
        $devis = Devi::find($deviId);

        if (!$devis) {
            return response()->json(['message' => 'Devis not found'], 404);
        }

        // Check if there is a related accept_action record
        $acceptAction = AcceptAction::where('devi_id', $deviId)->first();

        // Retrieve the associated offre_id
        $offreId = $devis->offre_id;

        // Update the status of the current Devis and related Devis
        Devi::where('offre_id', $offreId)->update(['status' => 'Annulé']);
        $devis->update(['status' => 'Accepté']);

        // Insert a new row into the accept_actions table
        AcceptAction::create([
            'devi_id' => $deviId,
            // Set the price as needed
            'prix' => $request->input('prix'),
        ]);

        return response()->json(['message' => 'Devis updated successfully']);
    }

        
}
