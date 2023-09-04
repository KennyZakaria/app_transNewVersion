<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Categorie;

class ConfigController extends Controller
{
     
    public function getCategorie()
    {
        $categorie = Categorie::all();
        return response()->json($categorie);
    }

}
