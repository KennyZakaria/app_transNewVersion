<?php

namespace App\Http\Controllers\API;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Devi;
use App\Models\Offre;
use App\Models\Place;
use App\Models\AcceptAction;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\API\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeviController    extends BaseController
{
    public function addDevi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'typeVehicule' => 'required',
            'dateDebut' => 'nullable|date',
            'dateFin' => 'nullable|date',
            'description' => 'string',
            'flexibleDate' => 'boolean',
            'prix' => 'nullable|numeric',
            'offre.id' => 'required|exists:offres,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $offre = $request->input('offre');

        $existingDevi = Devi::where('offre_id', $offre["id"])
            ->where('transporteur_id', $request->user()->id)
            ->where('status', 'EnCours')
            ->first();

       /* if ($existingDevi) {
            return $this->sendError('Un devis existe déjà pour cette offre.', [], 422);
        }*/

        $newDeviData = [
            'date' => Carbon::now(),
            'prix' => $request->input('prix'),
            'status' => 'EnCours',
            'offre_id' => $offre["id"],
            'transporteur_id' => $request->user()->id,
            'dateDebut' => $request->input('dateDebut'),
            'dateFin' => $request->input('dateFin'),
            'description' => $request->input('description'),
            'flexibleDate' => $request->input('flexibleDate'),
            'typeVehicule' => $request->input('typeVehicule'),
        ];
        $devisCreated=Devi::create($newDeviData);
        $offerCom = Offre::find($offre["id"]);
        NotificationHelper::insertNotification($offerCom["client_id"],"nouveauDevis",$devisCreated->id);
        return $this->sendResponse($devisCreated, 'Devi added successfully.');
    }
    public function getDevisById(Request $request, $deviId){
        $devi = Devi::where('id', $deviId)->with('offre')
        ->where('transporteur_id', $request->user()->id)
        ->first();
        return $this->sendResponse($devi, 'devis found');
    }
    public function updateDevi(Request $request, $deviId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'prix' => 'numeric',
                'typeVehicule' => 'string',
                'description' => 'string',
                'flexibleDate' => 'boolean',
                'dateDebut' => 'nullable|date',
                'dateFin' => 'nullable|date',
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

            if ($request->has('description')) {$devi->description = $request->input('description');}
            if ($request->has('flexibleDate')) {$devi->flexibleDate = $request->input('flexibleDate');}
            if ($request->has('prix')) { $devi->prix = $request->input('prix');}
            if ($request->has('typeVehicule')) { $devi->typeVehicule = $request->input('typeVehicule');}
            if ($request->has('dateDebut')) {
                $dateDebut = $request->input('dateDebut');
                if (strtotime($dateDebut) !== false) {
                    $devi->dateDebut = $dateDebut;
                }
            }
            if ($request->has('dateFin')) {
                $dateFin = $request->input('dateFin');
                if (strtotime($dateFin) !== false) {
                    $devi->dateFin = $dateFin;
                }
            }

            $devi->save();

            return $this->sendResponse($devi, 'Devi updated successfully.');
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while processing the request.'], 500);
        }

    }
    public function acceptDevi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'devis.id' => 'required|exists:devis,id',
            'observations'=>'string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }
        $deviId = $request->input('devis.id');
        $devis = Devi::with('offre')->find($deviId);

        if (!$devis) {
            return response()->json(['message' => 'Devis not found'], 404);
        }
        if ($devis->offre->client->id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $acceptAction = AcceptAction::where('devi_id', $deviId)->first();
        $offreId = $devis->offre_id;
        Devi::where('offre_id', $offreId)->update(['status' => 'Annule']);
        NotificationHelper::insertNotification($devis->transporteur_id,"devisAccepteParClient",$devis->id);
        $devis->update(['status' => 'Accepte']);
        if($acceptAction){
            return response()->json(['message' => 'Une AcceptAction existe déjà pour cette offre'], 404);
        }
        AcceptAction::create([
            'devi_id' => $deviId,
            'prix' =>$devis->prix,
            'date' => Carbon::now(),
            'observations'=>$request->input('observations')
        ]);
        Offre::where('id', $offreId)->update(['status' => 'Termine']);
        return $this->sendResponse('Devis Accepted successfully.', 'Devis Accepted successfully.');
       // return response()->json(['message' => 'Devis updated successfully']);
    }

    public function rejeterDevi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'devis.id' => 'required|exists:devis,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }
        $deviId = $request->input('devis.id');
        $devis = Devi::with('offre')->find($deviId);

        if (!$devis) {
            return response()->json(['message' => 'Devis not found'], 404);
        }
        if ($devis->offre->client->id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        NotificationHelper::insertNotification($devis->transporteur_id,"devisRejeteParClient",$devis->id);
        $devis->update(['status' => 'Annule']);
        return $this->sendResponse('Devis rejected successfully.', 'Devis rejected successfully.');
       // return response()->json(['message' => 'Devis updated successfully']);
    }

    public function getDevi(Request $request, $status) {
        $devis = Devi::with('offre', 'offre.placeDepart', 'offre.placeArrivee', 'offre.categorie')
            ->where('transporteur_id', Auth::id())
            ->where('status', $status);

        if ($request->has('dateDebut')) {

            $dateDebut = $request->input('dateDebut');
            $devis->whereHas('offre', function ($query) use ($dateDebut) {
                $query->where('dateDebut', '>=', $dateDebut);
            });
        }
        if ($request->has('dateFin')) {
            $dateFin = $request->input('dateFin');
            $devis->whereHas('offre', function ($query) use ($dateFin) {
                $query->where('dateFin', '<=', $dateFin);
            });
        }
        $devis = $devis->get();
        $listdevis = $devis->map(function ($devi) {
            $plcDe = Place::where('id', $devi->offre->placeDepart)->first();
            $plcAr = Place::where('id', $devi->offre->placeArrivee)->first();
            $transporteurId = auth()->user()->id;
            $existingDevi = Devi::where('offre_id', $devi->offre->id)
                ->where('transporteur_id', $transporteurId)
                ->first();

            if ($existingDevi) {
                $existingDevi->offre->alreadySubmit = true;
            } else {
                $devi->offre->alreadySubmit = false;
            }
            unset($devi->offre->placeDepart);
            unset($devi->offre->placeArrivee);
            $devi->offre->placeDepart = $plcDe;
            $devi->offre->placeArrivee = $plcAr;

            return $devi;
        });

        return response()->json(['message' => 'list Devis retrieved successfully', 'data' => $listdevis], 200);
    }
    public function getDevisClientByStatus(Request $request, $status)
    {
        $devis = Devi::with('offre', 'offre.placeDepart', 'offre.placeArrivee', 'offre.categorie')
        ->whereHas('offre', function ($query) {
            $query->where('client_id', Auth::id());
        });
        if($status != ""){
            $devis = $devis->where('status', $status);
        }
        $perPage = $request->input('per_page', 10);
        $devis = $devis->paginate($perPage);
        //$devis = $devis->get();

        $listdevis = $devis->map(function ($devi) {
            $plcDe = Place::where('id', $devi->offre->placeDepart)->first();
            $plcAr = Place::where('id', $devi->offre->placeArrivee)->first();

            //$plcDe = Place::find($devi->offre->placeDepart);
           // $plcAr = Place::find($devi->offre->placeArrivee);
            unset($devi->offre->placeDepart);
            unset($devi->offre->placeArrivee);
            $devi->offre->placeDepart = $plcDe;
            $devi->offre->placeArrivee = $plcAr;
            return $devi;
        });

        return response()->json(['message' => 'list Devis retrieved successfully', 'devis' => $devis], 200);
    }

    public function getDevisByConnectedClient(Request $request)
    {
        $devis = Devi::with('offre','transporteur')
        ->whereHas('offre', function ($query) {
            $query->where('client_id', Auth::id());
        });

        $perPage = $request->input('per_page', 10);
        $devis = $devis->paginate($perPage);
        //$devis = $devis->get();

        $listdevis = $devis->map(function ($devi) {
            $plcDe = Place::where('id', $devi->offre->placeDepart)->first();
            $plcAr = Place::where('id', $devi->offre->placeArrivee)->first();

            //$plcDe = Place::find($devi->offre->placeDepart);
           // $plcAr = Place::find($devi->offre->placeArrivee);
            unset($devi->offre->placeDepart);
            unset($devi->offre->placeArrivee);
            $devi->offre->placeDepart = $plcDe;
            $devi->offre->placeArrivee = $plcAr;
            return $devi;
        });

        return response()->json(['message' => 'list Devis retrieved successfully', 'devis' => $devis], 200);
    }
    public function getDevisClientById($id){
        try {
            $devis = Devi::with('offre', 'offre.placeDepart', 'offre.placeArrivee', 'offre.categorie', 'transporteur:id,status')
                ->whereHas('offre', function ($query) {
                    $query->where('client_id', Auth::id());
                })
                ->where('id', $id)
                ->first();
            if (!$devis) {
                return $this->sendError('Devis not found.', [], 404);
            }

            $plcDe = Place::where('id', $devis->offre->placeDepart)->first();
            $plcAr = Place::where('id', $devis->offre->placeArrivee)->first();
            unset($devis->offre->placeDepart);
            $devis->offre->placeDepart = $plcDe;
            unset($devis->offre->placeArrivee);
            $devis->offre->placeArrivee = $plcAr;

            return $this->sendResponse($devis, 'Devis found.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred while processing the request.', [], 500);
        }

    }
    public function getDevisByOffreId($id){
        $devis = Devi::where('offre_id', $id)
    ->where('transporteur_id', Auth::id())
    ->first();

      /* if ($devis->isEmpty()) {
            return $this->sendError( NULL, ' NOT found.');
        }*/
        return $this->sendResponse($devis, '  found.');

    }


    public function getDevisByOffreId2(Request $request, $id)
    {
        $devis = Devi::with('transporteur.user')
        ->whereHas('offre', function ($query) {
            $query->where('client_id', Auth::id());
        });
        if($id != -1){
            $devis = $devis->where('offre_id', $id);

        }else{
            return $this->sendError('Offer not found.', ['error' => 'Devi not found add an offre first'], 404);
        }

        $perPage = $request->input('per_page', 10);
        $devis = $devis->paginate($perPage);
        return response()->json(['message' => 'list Devis retrieved successfully', 'devis' => $devis], 200);
    }
    public function deleteDevis($deviId)
    {
        try {
            $devi = Devi::find($deviId);
            if (!$devi) {
                return $this->sendError('Offer not found.', ['error' => 'Devi not found'], 404);

            }
            $devi->delete();
            return response()->json(['message' => 'devi deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return $this->sendError('devi not found.', ['error' => 'devi not found'], 404);
        }
    }



}
