<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\Devi;
use App\Models\Place;
use App\Helpers\OfferHelper;
use App\Http\Controllers\API\BaseController as BaseController; 
class OfferTransporteurController extends BaseController
{
    public function index(Request $request)
    { 
        $query = Offre::with(['categorie', 'photos', 'placeDepart', 'placeArrivee', 'articles.dimension', 'chargement','devis.acceptAction','devis.transporteur']);
        
        $query->where('status', 'valide');
         
        $transporteurId = auth()->user()->id;
        $perPage = $request->input('per_page', 10);
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
