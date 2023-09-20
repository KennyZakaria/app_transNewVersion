<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviResource extends JsonResource
{
    public function toArray($request)
    {
        dd($this->offre->categorie["nomFr"]);
        return [
            'id' => $this->id,
            'date' => $this->date,
            'prix' => $this->prix,
            'status' => $this->status,
            'offre_id' => $this->offre_id,
            'transporteur_id' => $this->transporteur_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'offre' => [
                'id' => $this->offre->id,
                'dateDebut' => $this->offre->dateDebut,
                'dateFin' => $this->offre->dateFin,
                'client_id' => $this->offre->client_id,
                'categorie' => [
                    //'id' => $this->offre->categorie->id,
                    'nomFr' => $this->offre->categorie->nomFr,
                    'nomAr' => $this->offre->categorie->nomAr,
                    'nomAn' => $this->offre->categorie->nomAn,
                    'icon' => $this->offre->categorie->icon,
                    'created_at' => $this->offre->categorie->created_at,
                    'updated_at' => $this->offre->categorie->updated_at,
                ],
                'status' => $this->offre->status,
                'description' => $this->offre->description,
                'prix' => $this->offre->prix,
                'created_at' => $this->offre->created_at,
                'updated_at' => $this->offre->updated_at,
                'photosUrls' => $this->offre->photosUrls,
                'alreadySubmit' => $this->alreadySubmit, // Include the alreadySubmit property
            ],
        ];
    }
}
