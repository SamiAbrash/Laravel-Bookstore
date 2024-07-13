<?php

namespace App\Http\Controllers;


use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index','show','search');
    }

    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        try {
            $books = $request->input('books') ? $request->input('books') : [$request->all()];

            $request->validate([
                'books' => 'array',
                'books.*.title' => 'required|string|max:255',
                'books.*.author' => 'required|string|max:255',
                'books.*.publisher' => 'required|string|max:255',
                'books.*.firstPubDate' => 'required|date',
                'books.*.ifTranslator' => 'nullable|string|max:255',
                'books.*.description' => 'required|string',
                'books.*.isbn' => 'required|string|unique:books,isbn|max:20',
                'books.*.pages' => 'required|integer',
                'books.*.ifChapters' => 'nullable|string|max:255',
                'books.*.cover' => 'nullable|string'
            ]);
    
            $createdBooks = [];
            foreach ($books as $bookData) {
                $validator = \Validator::make($bookData, [
                    'title' => 'required|string|max:255',
                    'author' => 'required|string|max:255',
                    'publisher' => 'required|string|max:255',
                    'firstPubDate' => 'required|string',
                    'ifTranslator' => 'nullable|string|max:255',
                    'description' => 'required|string',
                    'isbn' => 'required|string|unique:books,isbn|max:20',
                    'pages' => 'required|integer',
                    'ifChapters' => 'nullable|string|max:255',
                    'cover' => 'nullable|string'
                ]);
    
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Validation Error',
                        'errors' => $validator->errors()
                    ], 422);
                }
    
                $createdBooks[] = Book::create($bookData);
            }
    
            return response()->json([
                'message : '=> 'Created successfully',
                'created : ' => $createdBooks, 
                'status : ' =>  201,
            ]);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors occurred
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            // Database error occurred
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Other unexpected errors occurred
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $book = Book::findOrFail($request->id);
    
        return response()->json([
            'message : ' => 'Found',
            'book : ' => $book,
        ]);
    }

    public function search(Request $request)
    {
        $query = Book::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->has('author')) {
            $query->where('author', 'like', '%' . $request->input('author') . '%');
        }

        if ($request->has('isbn')) {
            $query->where('isbn', 'like', '%' . $request->input('isbn') . '%');
        }

        if ($request->has('publisher')) {
            $query->where('publisher', 'like', '%' . $request->input('publisher') . '%');
        }

        $books = $query->get();

        return response()->json([
            'message : ' => 'Search results',
            'books : ' => $books,
        ]);
    }

    public function update(Request $req, Book $book)
    {
        $data = $req->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'publisher' => 'sometimes|string|max:255',
            'firstPubDate' => 'sometimes|string',
            'ifTranslator' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'isbn' => 'sometimes|string|unique:books,isbn|max:20',
            'pages' => 'sometimes|integer',
            'ifChapters' => 'sometimes|string|max:255',
            'cover' => 'sometimes|string'
        ]);
        
        $book->update($data);

        return response()->json([
            'message : ' => 'Book updated successfully',
            'book : ' => $book
        ]);
    }

    public function destroy(Book $book)
    {
            $book->delete();
            return response()->json([
                'message : ' => 'Book deleted successfully'
            ], 200);

    }
}
