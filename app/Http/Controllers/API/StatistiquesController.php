<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offre;
use App\Models\Devi;
use App\Models\AcceptAction;

class StatistiquesController extends Controller
{
    public function statistiques()
{
    $user_id = Auth::id();

    $statusCounts = Offre::where('client_id', $user_id)
        ->select('status', \DB::raw('COUNT(*) as count'))
        ->groupBy('status')
        ->pluck('count', 'status')
        ->toArray();

    $totalDevisCount = Devi::whereHas('offre', fn ($query) => $query->where('client_id', $user_id))->count();

    $totalBilling = AcceptAction::whereHas('devi.offre', fn ($query) => $query->where('client_id', $user_id))->sum('prix');

    $statuses = ['EnAttenteDeValidation', 'Valide', 'Termine', 'Rejete'];
    $result = array_fill_keys($statuses, 0);

    foreach ($statuses as $status) {
        if (array_key_exists($status, $statusCounts)) {
            $result[$status] = $statusCounts[$status];
        }
    }
    $result['nombreQuotes'] = $totalDevisCount;
    $result['biling'] = $totalBilling;

    return response()->json($result);
}
    
}
