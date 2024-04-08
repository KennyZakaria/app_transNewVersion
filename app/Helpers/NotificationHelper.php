<?php

namespace App\Helpers;

use App\Mail\ValidationMail;
use App\Mail\ValidationMailAdmin;
use App\Mail\RejectionMailAdmin;
use App\Mail\CreatedMail;
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
    public static function insertOfferStatusNotification($client_id,$notificationType,$offer){
        $user = User::find($client_id);
        $plcDe = Place::find($offer->placeDepart);
        $plcAr = Place::find($offer->placeArrivee);
        $categorie = Categorie::find($offer->categorie);
        $categoryName = optional($categorie)->nomFr;
        $contentNotification = "Votre ($plcDe->nomFr --> $plcAr->nomFr) demande a été acceptée et est maintenant publiée sur notre site.
        être prêt à recevoir des devis";


        switch ($notificationType) {
            case 'demandeAcceptee':
                $subject = "La demande a été validé.";
                Mail::to($user->email)->send(new ValidationMailAdmin($contentNotification, $subject));
                break;
            case 'demandeRejetee':
                $subject = "La demande a été Rejetée.";
                Mail::to($user->email)->send(new RejectionMailAdmin($contentNotification, $subject));
                break;
        }
        
    }
  


    public static function insertOfferCreatedNotification($client_id,$notificationType,$offer){
        $adminMail = 'client@transexpress.ma';
        $clientAppLink = 'https://transexpress.ma/offre';
        $adminAppLink = 'https://admin.transexpress.ma/offres';
        $dateOperation = Carbon::now()->toDateTimeString();
        $user = User::find($client_id);

        $plcDe = Place::find($offer->placeDepart);
        $plcAr = Place::find($offer->placeArrivee);
        $categorie = Categorie::find($offer->categorie);
        $categoryName = optional($categorie)->nomFr;
        $contentNotification = "Votre ($plcDe->nomFr --> $plcAr->nomFr) demande a été bien crée est maintenant en cours de validation.";
        $clientAppLink = $clientAppLink . "/$offer->id/view";

        $subject = "Votre demande ($categorie->nomFr) est en cours de validation";

        Mail::to($user->email)->send(new CreatedMail($contentNotification, $subject,$clientAppLink));
        Mail::to($adminMail)->send(new CreatedMail(
            $adminAppLink,
            "$user->firstName $user->lastName  demande en attant de validation",
            $adminAppLink
        ));
        // $user_id = ;
        // $notificationType = "nouveauDemande",
        // $deviDemandeCompte = ;
        // $contentNotification = ;
        // Notification::create([
        //     'user_id' => $user_id,
        //     'notificationType' => $notificationType,
        //     'deviDemandeCompteId' => $deviDemandeCompte,
        //     'notificationContent'=> $contentNotification,
        // ]);
    }
    public static function insertNotification($user_id, $notificationType, $deviDemandeCompte = null)
    {
        $dateOperation = Carbon::now()->toDateTimeString();
        $user = User::find($user_id);
        $contentNotification = "";
        switch ($notificationType) {
            case 'demandeCree':
                $offer = Offre::with(['categorie', 'placeDepart', 'placeArrivee'])->find($deviDemandeCompte);
                $plcDe = Place::find($offer->placeDepart);
                $plcAr = Place::find($offer->placeArrivee);
                $categorie = Categorie::find($offer->categorie);
                $categoryName = optional($categorie)->nomFr;
                $contentNotification = "Votre ($plcDe->nomFr --> $plcAr->nomFr) demande a été bien cree est maintenant en attant de validation.";
                $subject = "La demande a été crée.";
                break;
            case 'demandeAcceptee':
                $offer = Offre::with(['categorie', 'placeDepart', 'placeArrivee'])->find($deviDemandeCompte);
                $plcDe = Place::find($offer->placeDepart);
                $plcAr = Place::find($offer->placeArrivee);
                $categorie = Categorie::find($offer->categorie);
                $categoryName = optional($categorie)->nomFr;
                $contentNotification = "Votre ($plcDe->nomFr --> $plcAr->nomFr) demande a été acceptée et est maintenant publiée sur notre site.
                être prêt à recevoir des devis";

                $subject = "La demande a été validé.";
                break;

            case 'demandeRejetee':
                $offer = Offre::with(['categorie', 'placeDepart', 'placeArrivee'])->find($deviDemandeCompte);
                $plcDe = Place::find($offer->placeDepart);
                $plcAr = Place::find($offer->placeArrivee);
                $categorie = Categorie::find($offer->categorie);
                $categoryName = optional($categorie)->nomFr;
                $contentNotification = "La demande de service $categoryName de départ $plcDe->nomFr et L'arrivée $plcAr->nomFr a été rejetée. ";

                $subject = "La demande a été rejetée.";
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
