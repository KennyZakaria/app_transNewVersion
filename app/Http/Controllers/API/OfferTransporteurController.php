<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transporteur;
use App\Models\Offre;
use App\Models\Devi;
use App\Models\Place;
use App\Helpers\OfferHelper;
use App\Http\Controllers\API\BaseController as BaseController;
class OfferTransporteurController extends BaseController
{

    public function checkIfAprouved(Request $request){
        $user = $request->user();
        $transporteur = Transporteur::where('user_id', $user->id)->first();
        $isApprouved =  $transporteur->approuver;
        return response()->json(['isApprouved'=>$isApprouved]);
    }
    public function index(Request $request)
    {
        
 

        $query = Offre::with(['categorie', 'photos', 'placeDepart', 'placeArrivee', 'articles.dimension', 'chargement','devis.acceptAction','devis.transporteur']);

        $query->where('status', 'valide');
        if ($request->has('categorie')) {
            $categorie = $request->input('categorie');
            $query->where('categorie', $categorie);
        }
        if ($request->has('dateDebut')) {
            $dateDebut = $request->input('dateDebut');
            $query->where('dateDebut', '>=', $dateDebut);
        }
        if ($request->has('dateFin')) {
            $dateFin = $request->input('dateFin');
            $query->where('dateFin', '<=', $dateFin);
        }
        if ($request->has('placeDepart')) {
            $placeDepart = $request->input('placeDepart');
            $query->whereHas('placeDepart', function ($query) use ($placeDepart) {
                $query->where('nomFr', $placeDepart);
            });
        }
        if ($request->has('placeArrivee')) {
            $placeArrivee = $request->input('placeArrivee');
            $query->whereHas('placeArrivee', function ($query) use ($placeArrivee) {
                $query->where('nomFr', $placeArrivee);
            });
        }
    
        $transporteurId = auth()->user()->id;
        $perPage = $request->input('per_page', 20);

        $query->orderBy('created_at', 'desc');
       
        $offres = $query->paginate($perPage);
        $offresArray = $offres->toArray();
        OfferHelper::modifyKeysInOffersAndExist($offresArray,$transporteurId);
        return response()->json(['offers' => $offresArray]);
    }
    public function show($id)
    {

        $offer = Offre::with(['categorie','photos', 'placeDepart', 'placeArrivee', 'articles.dimension', 'chargement'])->find($id);

        if (!$offer) {
            return $this->sendError('Offer not found.', ['error' => 'Offer not found'], 404);
        }
        $plcDe=Place::find($offer->placeDepart);
        $plcAr=Place::find($offer->placeArrivee);

        OfferHelper::modifyObjectProperties($offer);
        $transporteurId = auth()->user()->id;
        $devi = Devi::where('offre_id', $offer['id'])
        ->where('transporteur_id',  $transporteurId)->first();
        if ($devi) {
            $offer['alredaySubmit']=true;
        }else{
            $offer['alreadySubmit']=false;
        }
        $offer['placeDepart']=$plcDe;
        $offer['placeArrivee']=$plcAr;
        return $this->sendResponse($offer, 'offer found.');
    }
}
