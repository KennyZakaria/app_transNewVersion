<?php

namespace App\Http\Controllers;

use App\Helpers\NotificationHelper;
use App\Models\Notification;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $roleName = 'ROLE_CLIENT';
        $query = User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        });
        if ($request->filled('firstName')) {
            $query->where('firstName', 'like', '%' . $request->input('firstName') . '%');
        }

        if ($request->filled('lastName')) {
            $query->where('lastName', 'like', '%' . $request->input('lastName') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->filled('status')) {
            $status = $request->input('status') == 'false' ?  false:  true;
             $query->where('desactiver', $status);
        }

        $clients = $query->paginate(10);

        $notifications = Notification::getUnreadCompteCreeNotifications();  
        return view('clients.index', ['clients' => $clients,'notifications' =>$notifications]);
    }
    public function desactiver($id)
    {
        try {
            $client = User::findOrFail($id);
            $username = $client->lastName;
            $client->desactiver = !$client->desactiver;
            $client->save();
            if($client->desactiver){
                NotificationHelper::insertNotification($id,"compteApprouve",$id);
            }else{
                NotificationHelper::insertNotification($id,"compteRejete",$id);
            }


            $message = "Le statut du compte client $username a été mis à jour avec succès.";
            return redirect()->back()->with('success', $message);
        } catch (ModelNotFoundException $e) {
            abort(404, 'Client not found');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the client status.');
        }
    }
}
