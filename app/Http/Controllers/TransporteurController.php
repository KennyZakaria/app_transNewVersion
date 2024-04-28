<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Transporteur;
use App\Models\Notification;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransporteurController extends Controller
{

    public function index(Request $request)
    {
        $query = Transporteur::withCount('devis')->with('user');

        if ($request->filled('firstName')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('firstName', 'like', '%' . $request->input('firstName') . '%');
            });
        }

        if ($request->filled('lastName')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('lastName', 'like', '%' . $request->input('lastName') . '%');
            });
        }

        if ($request->filled('email')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('email', 'like', '%' . $request->input('email') . '%');
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status') == 'false' ? false : true;
            $query->whereHas('user', function ($userQuery) use ($status) {
                $userQuery->where('desactiver', $status);
            });
        }
        $query->orderBy('created_at', 'desc');
        $transporteurs = $query->paginate(10);

        $notifications = Notification::getUnreadCompteCreeNotifications();
        return view('transporteurs.index', ['transporteurs' => $transporteurs,'notifications' =>$notifications]);
    }
    public function desactiver($id)
    {
        try {
            $client = User::findOrFail($id);
            $username = $client->lastName;
            $client->desactiver = !$client->desactiver;
            $client->save();

            $message = "Le statut du compte transporteur $username a été mis à jour avec succès.";
            return redirect()->back()->with('success', $message);
        } catch (ModelNotFoundException $e) {
            abort(404, 'Client not found');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the client status.');
        }
    }
    public function toggleApprouver($id)
    {
        $transporteur = Transporteur::findOrFail($id);
        $transporteur->approuver = !$transporteur->approuver;
        if($transporteur->approuver){
            NotificationHelper::insertAccountStatusNotification($id,"compteApprouve");
        }else{
            NotificationHelper::insertAccountStatusNotification($id,"compteRejete");
        }

        $transporteur->save();
        return redirect()->back()->with('success', 'Le statut d\'approbation a été modifié avec succès.');
    }
    public function detials($id)
    {
        $transporteur=Transporteur::with("user")->find($id);
        $reviews = Review::where('transporteur_id', $transporteur->id)->get();
        $revlist = [];
        $totalStars=0;
        $overAllRating=0;

        foreach ($reviews as &$review) {
            $totalStars += $review->numStars;
            // array_push($revlist ,$review->numStars);
            
        }
        if (count($reviews)>0) {
            $overAllRating =  $totalStars / count($reviews);
        }
        $notifications = Notification::getUnreadCompteCreeNotifications();
        return view('transporteurs.detail',['transporteur' => $transporteur], ['rating' => $overAllRating ,'notifications' =>$notifications ]);
    }
}
