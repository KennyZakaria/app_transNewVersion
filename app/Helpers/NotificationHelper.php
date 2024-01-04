<?php

namespace App\Helpers;

use App\Mail\ValidationMail;
use App\Models\Categorie;
use App\Models\Devi;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\Place;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class NotificationHelper
{
    public static function insertNotification($user_id, $notificationType, $deviDemandeCompte = null)
    {
        $dateOperation = Carbon::now()->toDateTimeString();
        $user = User::find($user_id);
        $contentNotification = "";
        switch ($notificationType) {
            case 'demandeAcceptee':
                $offer = Offre::with(['categorie', 'placeDepart', 'placeArrivee'])->find($deviDemandeCompte);
                $plcDe = Place::find($offer->placeDepart);
                $plcAr = Place::find($offer->placeArrivee);
                $categorie = Categorie::find($offer->categorie);
                $categoryName = optional($categorie)->nomFr;
                $contentNotification = "Le demande de service $categoryName de départ $plcDe->nomFr et L'arrivée $plcAr->nomFr a été acceptée";

                $subject = "Le demande a été acceptée.";
                break;

            case 'demandeRejetee':
                $offer = Offre::with(['categorie', 'placeDepart', 'placeArrivee'])->find($deviDemandeCompte);
                $plcDe = Place::find($offer->placeDepart);
                $plcAr = Place::find($offer->placeArrivee);
                $categorie = Categorie::find($offer->categorie);
                $categoryName = optional($categorie)->nomFr;
                $contentNotification = "Le demande de service $categoryName de départ $plcDe->nomFr et L'arrivée $plcAr->nomFr a été rejetée. ";

                $subject = "Le demande a été rejetée.";
                break;

            case 'nouveauDevis':
                $devi = Devi::with('offre','transporteur.user')->find($deviDemandeCompte);
                $offer = Offre::with(['categorie', 'placeDepart', 'placeArrivee'])->find($devi->offre->id);
                $plcDe = Place::find($offer->placeDepart);
                $plcAr = Place::find($offer->placeArrivee);
                $categorie = Categorie::find($offer->categorie);
                $categoryName = optional($categorie)->nomFr;
                $contentNotification = "Nouveau devis de la demande de service $categoryName de départ
                $plcDe->nomFr et L'arrivée $plcAr->nomFr par le transporteur ". $devi->transporteur->user->firstName ;

                $subject = "Nouveau devis disponible.";
                break;

            case 'compteApprouve':
                $contentNotification = "Votre compte a été approuvé. " . $dateOperation;
                $subject = "Compte approuvé.";
                break;

            case 'compteRejete':
                $contentNotification = "Votre compte a été rejeté. " . $dateOperation;
                $subject = "Compte rejeté.";
                break;

            case 'devisAccepteParClient':
                $devi = Devi::with('offre','transporteur.user')->find($deviDemandeCompte);
                $offer = Offre::with(['categorie', 'placeDepart', 'placeArrivee'])->find($devi->offre->id);
                $plcDe = Place::find($offer->placeDepart);
                $plcAr = Place::find($offer->placeArrivee);
                $categorie = Categorie::find($offer->categorie);
                $categoryName = optional($categorie)->nomFr;
                $contentNotification = "Le devis concernant la demande de service $categoryName de départ
                $plcDe->nomFr et L'arrivée $plcAr->nomFr a été accepté par le client";
                $subject = "Devis accepté par le client.";
                break;

            case 'devisRejeteParClient':
                $contentNotification = "Le devis a été rejeté par le client. " . $dateOperation;
                $subject = "Devis rejeté par le client.";
                break;

            default:
                $contentNotification = "Notification par défaut. " . $dateOperation;
                $subject = "Notification par défaut.";
                break;
        }
        Mail::to($user->email)->send(new ValidationMail($contentNotification, $subject));
        Notification::create([
            'user_id' => $user_id,
            'notificationType' => $notificationType,
            'deviDemandeCompteId' => $deviDemandeCompte,
            'notificationContent'=> $contentNotification,
        ]);
    }
    public static function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->update(['statusRead' => true]);
            return true;
        }
        return false;
    }
}
