<?php
namespace App\Helpers;
use App\Models\Devi;
class OfferHelper {
    public static function modifyKeysInOffers(&$offersArray) {
        foreach ($offersArray['data'] as &$offer) {
            $offer['placeDepart'] = $offer['place_depart']; 
            unset($offer['place_depart']); // Remove the old key
            $offer['placeArrivee'] = $offer['place_arrivee'];
            unset($offer['place_arrivee']); // Remove the old key
        }
    }
    public static function modifyKeysInOffersAndExist(&$offersArray,$transporteurId) {
        foreach ($offersArray['data'] as &$offer) {
            $offer['placeDepart'] = $offer['place_depart']; 
            $devi = Devi::where('offre_id', $offer['id'])
            ->where('transporteur_id',  $transporteurId)->first();
            if ($devi) {
                $offer['alredaySubmit']=true;
            }else{
                $offer['alreadySubmit']=false;
            }
           
            
            unset($offer['place_depart']); // Remove the old key
            $offer['placeArrivee'] = $offer['place_arrivee'];
            unset($offer['place_arrivee']); // Remove the old key
        }
    }
    public static function modifyObjectProperties(&$offer)
    { 
        unset($offer->placeDepart);
        unset($offer->placeArrivee);
    }
}
