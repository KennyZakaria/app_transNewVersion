<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Helpers\OfferHelper;
use App\Models\Notification;
use App\Models\Categorie;
use App\Models\Devi;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class DeviController extends Controller
{

    public function index(Request $request)
        {   
            $query = Devi::with([ 
            'transporteur.user' => function ($query) {
                $query->select('id', 'firstName', 'lastName', 'email'); 
            }]);
        
            $date = $request->input('date');
            $prix = $request->input('prix');
            $offre_id = $request->input('offre_id');
            $transporteur_id = $request->input('transporteur_id');
            $status = $request->input('status');
            $typeVehicule = $request->input('typeVehicule');
            $dateDebut = $request->input('dateDebut');
            $dateFin = $request->input('dateFin');
            $description = $request->input('description');
            $flexibleDate = $request->input('flexibleDate');
        
            if ($date) {
                $query->where('date', '>=', $date);
            }
            if ($prix) {
                $query->where('prix', $prix);
            }
        
            if ($typeVehicule) {
                $query->where('typeVehicule', $typeVehicule);
            }


            $devises = $query->paginate(10);
            $devisesArray = $devises->toArray();
            
            $notifications = Notification::getUnreadCompteCreeNotifications();
            return view('devises.index', ['devises' => $devises,'devisesArray' => $devisesArray,'notifications' =>$notifications ]);
        }

}
