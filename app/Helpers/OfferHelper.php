<?php
namespace App\Helpers;

class OfferHelper {
    public static function modifyKeysInOffers(&$offersArray) {
        foreach ($offersArray['data'] as &$offer) {
            $offer['placeDepart'] = $offer['place_depart'];
            unset($offer['place_depart']); // Remove the old key
            $offer['placeArrivee'] = $offer['place_arrivee'];
            unset($offer['place_arrivee']); // Remove the old key
        }
    }
}
