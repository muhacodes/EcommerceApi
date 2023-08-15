<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        // If you want to have 
        // return response()->json(['categories' => $categories], 200);
        return response()->json($categories, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|unique:categories|max:50|min:5',
        //     // Add any other validation rules for the category fields
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        // }
    
        // $category = Category::create($request->all());
    
        // return response()->json([
        //     'message' => 'Category created successfully',
        //     'category' => $category
        // ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }




    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:50|min:3',
            // Add any other validation rules for the category fields
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
    
        $category = Category::create($request->all());
    
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
    
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:categories|max:50|min:5',
                // Add any other validation rules for the category fields
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            // Update the category
            $category->update($request->all());
    
            return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
