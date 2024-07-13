<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Book;

class ReviewController extends Controller
{
    public function index(Book $book)
    {
        $reviews = $book->reviews;

         if ($reviews->count() == 0)
         {
            return response()->json([
                'Reveiws : ' => 'no reviews found',
            ]);
         }

         return response()->json([
            'Reviews : ' => $reviews,
         ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|integer|exists:books,id',
            'comment' => 'required|string|min:1|max:500',
            'rating' => ''
        ]);

        $review = new Review();
        $review->user_id = Auth::id();
        $review->book_id = $request->book_id;
        $review->comment = $request->comment;
        $review->save();

        return response()->json([
            'message : ' => 'created successfully',
            'Comment : ' => $review
        ],201);
    }

    public function show(Review $review)
    {
        return response()->json([
            'Review : ' => $review
        ],200);
    }

    public function edit(Request $request, Review $review)
    {
        $v = Validator::make(all(),[
            'comment' => 'required|string|min:1|max:500'
        ]);

        if ($v->fails())
        {
            return response()->json([
                $v->errors()
            ]);
        }

        $review->comment = $request->comment;

        return response()->json([
            'Message : ' => 'Updated successfully',
            'Updated : ' => $review
        ],200);
    }

    public function delete(Review $review)
    {
        $review->delete();
        return response()->json([
            'message : ' => 'deleted successfully'
        ],200);

    }
}