<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

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

    public function store(Request $request)
    {
        $request->validate([
            'transporterId' => 'required|exists:transporteurs,id',
            'numStars'      => 'required|integer|min:1|max:5',
            'comment'       => 'string',
        ]);

        $review = Review::create($request->all());
        return response()->json($review, 201); 
    }
}
