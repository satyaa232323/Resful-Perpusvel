<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return ResponseHelper::jsonResponse(false, 'No categories found', [], 404); 
        }
        
        else{
            return ResponseHelper::jsonResponse(true, 'Categories retrieved successfully', $categories, 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
       $request->validated();
       $categories = new Category();
       $categories->name = $request['name'];
       $categories->save();
         
       return ResponseHelper::jsonResponse(true, 'Category created successfully', $categories, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}