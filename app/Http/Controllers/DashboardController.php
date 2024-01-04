<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Devi;
use App\Models\Offre;
use App\Models\Transporteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth.custom', 'role:ROLE_ADMIN']);
    }
    public function index(Request $request)
    {
        $numClients = Client::count();
        $numTransporters = Transporteur::count();
        $numDemande = Offre::count();
        $numDevis = Devi::count();

        $topClients = DB::table('offres')
        ->join('clients', 'offres.client_id', '=', 'clients.id')
        ->join('users', 'clients.user_id', '=', 'users.id')
        ->select(
            'clients.id as client_id',
            'users.lastname',
            DB::raw('COUNT(offres.id) as offer_count')
        )
        ->groupBy('clients.id', 'users.lastname')
        ->orderByDesc('offer_count')
        ->take(10)
        ->get();

    $labels = $topClients->map(function ($client) {
        return $client->lastname;
    });

    $data = $topClients->pluck('offer_count');

     // Second chart data
     $categoriesWithCounts = Categorie::withCount('offers')->get();

     $categoryOfferCounts = $categoriesWithCounts->pluck('offers_count')->toArray();

     return view('dashbord.dashborad', compact('labels', 'data',
     'categoriesWithCounts', 'categoryOfferCounts','numClients','numTransporters','numDemande','numDevis'));
    }
}
