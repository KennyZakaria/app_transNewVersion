<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offre;
use App\Models\Place;
use App\Models\Photo;
use App\Models\Article;
use App\Models\Dimension;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Chargement;
use App\Helpers\OfferHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OffreController extends BaseController
{
    public function indexPublic(Request $request)
    {

        $dateDebut = $request->input('dateDebut');
        $dateFin = $request->input('dateFin');
        $placeDepart = $request->input('placeDepart');
        $placeArrivee = $request->input('placeArrivee');
        $categorie = $request->input('categorie');



        $query = Offre::with(['categorie', 'photos', 'placeDepart', 'placeArrivee', 'articles.dimension', 'chargement','devis.acceptAction']);


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
        $query->where('status', '=', "Valide");
        $perPage = $request->input('per_page', 10);
        $offres = $query->paginate($perPage);

         $offresArray = $offres->toArray();
         OfferHelper::modifyKeysInOffers($offresArray);
        return response()->json(['offers' => $offresArray]);

    }
    public function index(Request $request)
    {

        $dateDebut = $request->input('dateDebut');
        $dateFin = $request->input('dateFin');
        $placeDepart = $request->input('placeDepart');
        $placeArrivee = $request->input('placeArrivee');
        $categorie = $request->input('categorie');

        $client = Auth::user();

        $query = Offre::with(['categorie', 'photos', 'placeDepart', 'placeArrivee', 'articles.dimension', 'chargement','devis.acceptAction']);


        if ($request->has('dateDebut') && $request->has('dateFin')) {
            $query->whereBetween('dateFin', [$request->input('dateDebut'), $request->input('dateFin')]);
        } elseif ($request->has('dateDebut')) {
            $query->where('dateDebut', '>=', $request->input('dateDebut'));
        } elseif ($request->has('dateFin')) {
            $query->where('dateFin', '<=', $request->input('dateFin'));
        }

        if ($placeDepart) {

           $query->where(function ($query) use ($placeDepart) {
                $query->where('placeDepart', 'like', '%' . $placeDepart . '%')
                    ->orWhereHas('placeDepart', function ($subquery) use ($placeDepart) {
                        $subquery->where('nomFr', 'like', '%' . $placeDepart . '%');
                            /*->orWhere('nomAr', 'like', '%' . $placeDepart . '%')
                            ->orWhere('nomAn', 'like', '%' . $placeDepart . '%');*/
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
        $query->where('client_id',$client->id );
        $perPage = $request->input('per_page', 10);
        $offres = $query->orderBy('created_at', 'desc')->paginate($perPage);

         $offresArray = $offres->toArray();
         OfferHelper::modifyKeysInOffers($offresArray);
        return response()->json(['offers' => $offresArray]);

    }
    public function offresByStatus(Request $request,$status)
    {
        //$status = $request->input('status');
        $dateDebut = $request->input('dateDebut');
        $dateFin = $request->input('dateFin');
        $placeDepart = $request->input('placeDepart');
        $placeArrivee = $request->input('placeArrivee');
        $categorie = $request->input('categorie');

        $client = Auth::user();

        $query = Offre::with(['categorie', 'photos', 'placeDepart',
        'placeArrivee', 'articles.dimension', 'chargement',
        'devis.acceptAction','devis.transporteur:id,status']);
            //.user:id,firstName,lastName

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
 
        if ($status) {
            if ($status == "Delete") { 
                $query->withTrashed()->where('deleted_at', '!=', null);
            } else {
                $query->where('status', $status);
            }
        }
        if ($categorie) {
            $query->where('categorie', $categorie);
        }
        $query->where('client_id',$client->id );

        $perPage = $request->input('per_page', 10);
        $offres = $query->paginate($perPage);

         $offresArray = $offres->toArray();
         OfferHelper::modifyKeysInOffers($offresArray);
        return response()->json(['offers' => $offresArray]);

    }
    public function store(Request $request)
    {
        try {
            $validator = $this->validateRequest($request);
            $placeDepart=null;
            $placeArrivee=null;
            $categorie=null;
            if ($request->has('placeDepart') ) {
                $placeDepart = $this->createPlace($request->input('placeDepart'));
            }
            if ($request->has('placeArrivee') ) {
                $placeArrivee = $this->createPlace($request->input('placeArrivee'));
            }

            $categorie=$request->input('categorie');
            $clientId = auth()->id();
            $offre = $this->createOffre($validator->validated(), $placeDepart, $placeArrivee,$clientId,$categorie);

            if ($request->has('photos') ) {
                $this->handlePhotos($request->input('photos'), $offre);
            }
            if ($request->has('chargement')) {
                $this->handleChargement($request->input('chargement'), $offre);
            }
            $this->handleArticles($request->input('articles', []), $offre);

            $offre = $this->loadOffreRelations($offre);

            return response()->json([
                'message' => 'Offre created successfully',
                'offre' => $offre,
            ], 201);
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (QueryException $e) {
            return $this->handleDatabaseException($e);
        } catch (Exception $e) {
            return $this->handleUnexpectedException($e);
        }
    }

    protected function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'dateDebut' => 'nullable|date',
                'dateFin' => 'nullable|date',
                'categorie.id' => 'integer|exists:categories,id',
                'status' => 'in:EnAttenteDeValidation,Validé,Rejeté,Terminé',
                'description' => 'string',
                'prix' => 'numeric',
                'photosUrls'=>'array',
                'placeDepart.id' => 'integer',
                'placeDepart.nomFr' => 'string',
                'placeDepart.nomAr' => 'string',
                'placeDepart.nomAn' => 'string',
                'placeDepart.latitude' => 'numeric',
                'placeDepart.longitude' => 'numeric',
                'placeArrivee.id' => 'integer',
                'placeArrivee.nomFr' => 'string',
                'placeArrivee.nomAr' => 'string',
                'placeArrivee.nomAn' => 'string',
                'placeArrivee.latitude' => 'numeric',
                'placeArrivee.longitude' => 'numeric',
                'photos.*.size' => 'nullable|string',
                'photos.*.format' => 'nullable|string',
                'photos.*.nom' => 'nullable|string',
                'photos.*.url' => 'nullable|string',
                'chargement.chargement' => 'nullable|boolean',
                'chargement.dechargement' => 'nullable|boolean',
                'chargement.etageChargement' => 'nullable|integer',
                'chargement.etageDechargement' => 'nullable|integer',
                'chargement.ascenceurChargement' => 'nullable|boolean',
                'chargement.ascenceurDechargement' => 'nullable|boolean',
                'articles' => 'array', // Ensure 'articles' is an array
                'articles.*.designation' => 'string',
                'articles.*.quantite' => 'integer',
                'articles.*.dimension.dimensionX' => 'numeric',
                'articles.*.dimension.dimensionY' => 'numeric',
                'articles.*.dimension.dimensionZ' => 'numeric',
                'articles.*.dimension.uniteDimension' => 'string',
                'articles.*.dimension.poid' => 'numeric',
                'articles.*.dimension.unitePoids' => 'string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator;
    }

    protected function createPlace(array $placeData)
    {
        $id = $placeData['id'];
        $existingPlace = Place::find($id);

        if ($existingPlace) {
            return $existingPlace;
        } else {
            $place = Place::create($placeData); // Create the Place
            $place->id = $id; // Set the id attribute
            return $place; // Return the updated Place object
        }
    }

    protected function createOffre(array $data, Place $placeDepart, Place $placeArrivee, $clientId, $categorie)
    {

        $offre = new Offre();

        $offre->fill($data);

        if (isset($data['photosUrls']) && is_array($data['photosUrls'])) {

            $data['photosUrls'] = implode(';', $data['photosUrls']);
        }
        $offre->categorie = $categorie['id'];
        $offre->placeDepart()->associate($placeDepart);
        $offre->placeArrivee()->associate($placeArrivee);
        $offre->client_id = $clientId;
        $offre->save();
        return $offre;
    }

    protected function handlePhotos(array $photosData, Offre $offre)
    {
        foreach ($photosData as $photoData) {
            $photo = new Photo();
            $photo->fill($photoData);

            $photo->size = $photoData['size'];
            $photo->format = $photoData['format'];
            $photo->nom = $photoData['nom'];
            $photo->url = $photoData['url'];
            $offre->photos()->save($photo);
        }
    }

    protected function handleChargement(array $chargementData, Offre $offre)
    {
        $chargement = new Chargement();
        $chargement->fill($chargementData);
        $offre->chargement()->save($chargement);
    }

    protected function handleArticles(array $articlesData, Offre $offre)
    {
        foreach ($articlesData as $articleData) {
            $article = new Article();
            $article->fill($articleData);
            $offre->articles()->save($article);
            if (isset($articleData['dimension']) && is_array($articleData['dimension'])) {
                $dimensionData = $articleData['dimension'];
                $dimension = new Dimension($dimensionData);
                $article->dimension()->save($dimension);
            }
        }
    }

    protected function loadOffreRelations(Offre $offre)
    {
        return Offre::with(['placeDepart', 'placeArrivee', 'photos', 'chargement', 'articles.dimension'])->find($offre->id);
    }

    protected function handleValidationException(ValidationException $e)
    {
        return response()->json([
            'message' => 'Validation Error',
            'errors' => $e->validator->errors(),
        ], 400);
    }

    protected function handleDatabaseException(QueryException $e)
    {
        return response()->json([
            'message' => 'Database Error',
            'error' => $e->getMessage(),
        ], 500);
    }

    protected function handleUnexpectedException(Exception $e)
    {
        return response()->json([
            'message' => 'An unexpected error occurred',
            'error' => $e->getMessage(),
        ], 500);
    }
    public function show($id)
    {
        $offer = Offre::with(['categorie','photos', 'placeDepart', 'placeArrivee', 'articles.dimension', 'chargement'])->find($id);

        if (!$offer) {
            return $this->sendError('Offer not found.', ['error' => 'Offer not found'], 404);
        }
        $plcDe=Place::find($offer->placeDepart);
        $plcAr=Place::find($offer->placeArrivee);
        OfferHelper::modifyObjectProperties($offer);
        $offer['placeDepart']=$plcDe;
        $offer['placeArrivee']=$plcAr;
        return $this->sendResponse($offer, 'offer found.');
    }
    public function destroy($id)
    {
        try {
            $offre = Offre::find($id);
            if (!$offre) {
                return $this->sendError('Offer not found.', ['error' => 'Offer not found'], 404);

            }
            $offre->delete();
            return response()->json(['message' => 'Offer  deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return $this->sendError('Offer not found.', ['error' => 'Offer not found'], 404);
        }
    }

}
