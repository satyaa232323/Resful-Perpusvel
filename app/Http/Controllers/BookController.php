<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();

        if ($books->isEmpty()) {
            return ResponseHelper::jsonResponse(false, 'No books found', [], 404);
        }
        
        return ResponseHelper::jsonResponse(true, 'Books retrieved successfully', $books, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $request->validated();
        
        $books = new Book();

        $books->title = $request['title'];
        $books->author = $request['author'];
        $books->number_book = $request['number_book'];
        $books->publisher = $request['publisher'];
        $books->cover = $request['cover'];
        $books->publication_year = $request['publication_year'];
        $books->category_id = $request['category_id'];
        $books->stock = $request['stock'];
        $books->save();

        return ResponseHelper::jsonResponse(true, 'Book created successfully', $books, 201);    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found',
            ], 404);
        }

        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully',
        ], 200);
    }
}