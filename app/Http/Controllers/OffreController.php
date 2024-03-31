<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Helpers\OfferHelper;
use App\Models\Categorie;
use App\Models\Devi;
use App\Models\Message;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    /* public function index(Request $request)
    {
        $query = Offre::with(['categorie', 'photos', 'placeDepart', 'placeArrivee', 'articles.dimension', 'chargement', 'devis.acceptAction']);
        $categories = Categorie::all();
        $dateDebut = $request->input('dateDebut');
        $dateFin = $request->input('dateFin');
        $placeDepart = $request->input('placeDepart');
        $placeArrivee = $request->input('placeArrivee');
        $categorie = $request->input('categorie');

        if ($dateDebut) {
            $query->where('dateDebut', '>=', $dateDebut);
        }

        if ($dateFin) {
            $query->where('dateFin', '<=', $dateFin);
        }

        if ($placeDepart) {
            $query->where(function ($query) use ($placeDepart) {
                $query->where('placeDepart', 'like', '%' . $placeDepart . '%')
                    ->orWhereHas('placeDepart', function ($subquery) use ($placeDepart) {
                        $subquery->where('nomFr', 'like', '%' . $placeDepart . '%')
                            ->orWhere('nomAr', 'like', '%' . $placeDepart . '%')
                            ->orWhere('nomAn', 'like', '%' . $placeDepart . '%');
                    });
            });
        }

        if ($placeArrivee) {
            $query->where(function ($query) use ($placeArrivee) {
                $query->where('placeArrivee', 'like', '%' . $placeArrivee . '%')
                    ->orWhereHas('placeArrivee', function ($subquery) use ($placeArrivee) {
                        $subquery->where('nomFr', 'like', '%' . $placeArrivee . '%')
                            ->orWhere('nomAr', 'like', '%' . $placeArrivee . '%')
                            ->orWhere('nomAn', 'like', '%' . $placeArrivee . '%');
                    });
            });
        }

        if ($categorie) {
            $query->where('categorie', $categorie);
        }

        $offres = $query->paginate(10);
        //dd($offres);
        $offresArray = $offres->toArray();
        return view('offres.index', ['offres' => $offresArray,'categories' => $categories]);
    }*/
    public function index(Request $request)
    {   
        $query = Offre::with(['categorie', 
        'client.user' => function ($query) {
            $query->select('id', 'firstName', 'lastName', 'email'); 
        },
        'photos', 'placeDepart', 'placeArrivee', 'articles.dimension', 'chargement', 'devis.acceptAction']);
        $categories = Categorie::all();
        $dateDebut = $request->input('dateDebut');
        $dateFin = $request->input('dateFin');
        $placeDepart = $request->input('placeDepart');
        $placeArrivee = $request->input('placeArrivee');
        $categorie = $request->input('categorie');

        if ($dateDebut) {
            $query->where('dateDebut', '>=', $dateDebut);
        }

        if ($dateFin) {
            $query->where('dateFin', '<=', $dateFin);
        }

        if ($placeDepart) {
            $query->where(function ($query) use ($placeDepart) {
                $query->where('placeDepart', 'like', '%' . $placeDepart . '%')
                    ->orWhereHas('placeDepart', function ($subquery) use ($placeDepart) {
                        $subquery->where('nomFr', 'like', '%' . $placeDepart . '%')
                            ->orWhere('nomAr', 'like', '%' . $placeDepart . '%')
                            ->orWhere('nomAn', 'like', '%' . $placeDepart . '%');
                    });
            });
        }

        if ($placeArrivee) {
            $query->where(function ($query) use ($placeArrivee) {
                $query->where('placeArrivee', 'like', '%' . $placeArrivee . '%')
                    ->orWhereHas('placeArrivee', function ($subquery) use ($placeArrivee) {
                        $subquery->where('nomFr', 'like', '%' . $placeArrivee . '%')
                            ->orWhere('nomAr', 'like', '%' . $placeArrivee . '%')
                            ->orWhere('nomAn', 'like', '%' . $placeArrivee . '%');
                    });
            });
        }

        if ($categorie) {
            $query->where('categorie', $categorie);
        }

        $query->withCount('devis');

        $offres = $query->paginate(10);
        $offresArray = $offres->toArray();
        return view('offres.index', ['offres' => $offres, 'offersArray' => $offresArray, 'categories' => $categories]);
    }


    public function changeStatusOffre($id, $status)
    {
        $offre = Offre::findOrFail($id);
        $offre->status = $status;
        $offre->save();
        $demandeType = "demandeRejetee";
        if ($status === "Valide")
            $demandeType = 'demandeAcceptee';
        NotificationHelper::insertOfferStatusNotification($offre->client_id,$demandeType, $offre);
        return redirect()->route('offres.index')->with('success', 'Offre status updated successfully.');
    }
    public function listeDevis($IdDemande)
    {
        $offre = Offre::findOrFail($IdDemande);
        $devis = Devi::Where("offre_id", $IdDemande)->get();
        return view('offres.listeDevis', ['devis' => $devis, 'offre' => $offre]);
    }
    public function ChatDevi($deviId)
    {
        $message = Message::where('devi_id', $deviId)->first();

    if ($message) {
        $userSender = User::find($message->sender_id);
        $userReceiver = User::find($message->receiver_id);

        $user1 = $userSender;
        $user2 = $userReceiver;

        $user1Id = $userSender->id;
        $user2Id = $userReceiver->id;

        $messages = Message::where(function ($query) use ($user1Id, $user2Id) {
            $query->where('sender_id', $user1Id)->where('receiver_id', $user2Id);
        })->orWhere(function ($query) use ($user1Id, $user2Id) {
            $query->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
        })->orderBy('created_at', 'asc')->get();
    } else {
        $user1 = null;
        $user2 = null;
        $messages = collect();
    }

    return view('offres.chat', compact('user1', 'user2', 'messages'));
    }
}
