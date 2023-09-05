<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Categorie;
use App\Models\Ville; 

class ConfigController extends Controller
{
     
    public function getCategorie()
    {
        $categorie = Categorie::all();
        return response()->json($categorie);
    }
    public function AllVilles()
    {
        $villes = Ville::all();
        return response()->json($villes);
    }
    public function GetVille(Ville $ville)
    {
        return response()->json($ville);
    }

}
