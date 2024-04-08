<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Database\QueryException;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();
        return response()->json($reviews);
    }

    public function show($id)
    {
        $review = Review::findOrFail($id);
        
        return response()->json($review);

    }
    public function getTransporterReviews(Request $request, $transporterId)
    {
        try {
            $reviews = Review::with(['client.user' => function ($query) {
                $query->select('id', 'firstName', 'lastName', 'email'); 
            }])
            ->where('transporteur_id', $transporterId)
            ->get();
    
            if ($reviews->isEmpty()) {
                return response()->json($reviews, 201);
            }
    
            $reviews->transform(function ($review) {
                $review['user'] = $review->client->user;
                unset($review['client']);
                return $review;
            });
    
            return response()->json($reviews, 200);
        } catch (QueryException $exception) {
            return response()->json(['message' => 'Failed to retrieve reviews. Database error.'], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'transporteur_id' => 'required|exists:transporteurs,id',
            'numStars'      => 'required|integer|min:1|max:5',
            'comment'       => 'string',
        ]);

        $review = Review::create($request->all());
        return response()->json($review, 201); 
    }
}
