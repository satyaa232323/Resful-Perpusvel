<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\BookRequest;
use App\Http\Resources\Bookresource;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        
        return ResponseHelper::jsonResponse(true, 'Books retrieved successfully', 
            Bookresource::collection($books), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $validated = $request->validated();
        
        // Handle file upload
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('covers', $filename, 'public');
            $validated['cover'] = $path;
        }

        $book = Book::create($validated);

        return ResponseHelper::jsonResponse(true, 'Book created successfully', 
            new Bookresource($book), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return ResponseHelper::jsonResponse(false, 'Book not found', null, 404);
        }

        return ResponseHelper::jsonResponse(true, 'Book retrieved successfully', 
            new Bookresource($book), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return ResponseHelper::jsonResponse(false, 'Book not found', null, 404);
        }

        $validated = $request->validated();

        // Handle file upload if new cover is provided
        if ($request->hasFile('cover')) {
            // Delete old cover
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }

            $file = $request->file('cover');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('covers', $filename, 'public');
            $validated['cover'] = $path;
        }

        $book->update($validated);

        return ResponseHelper::jsonResponse(true, 'Book updated successfully', 
            new Bookresource($book), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return ResponseHelper::jsonResponse(false, 'Book not found', null, 404);
        }

        // Delete cover file if exists
        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return ResponseHelper::jsonResponse(true, 'Book deleted successfully', null, 200);
    }
}