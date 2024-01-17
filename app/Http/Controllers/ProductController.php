<?php

namespace App\Http\Controllers;
use App\Models\category;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $products = Product::all();
        // $products->transform(function ($product) {
        //     $product->photo = $product->photo ? Storage::disk('public')->url($product->photo) : null;
        //     // $product->photo1 = $product->photo1 ? Storage::disk('public')->url($product->photo1) : null;
        //     return $product;
        // });

        // return response()->json($products    , 200);

        // Transform the products to include image URLs
        // $products->transform(function ($product) {
        //     $product->photo_url = $product->photo ? Storage::disk('public')->url($product->photo) : null;
        //     $product->photo1_url = $product->photo1 ? Storage::disk('public')->url($product->photo1) : null;
        //     return $product;
        // });
        
        // return response()->json([
        //     'products' => $products
        // ], 200);
        // $product = Product::with('variations')->get();

        // $productData = new ProductVariationResource($product);
        
    
        // return response()->json($productData, 200);

        $products = Product::all();
        $productCollection = ProductResource::collection($products);

        return response()->json($productCollection, 200);

        // $products->transform(function ($product) {
        //     $product->photo_url = $product->photo ? url('storage/' . $product->photo) : null;
        //     $product->photo1_url = $product->photo1 ? url('storage/' . $product->photo1) : null;
        //     return $product;
        // });

        // return ProductResource::collection($products);


        // $products =  Product::with('variations')->get();
        // $productData = ProductVariationResource($products);
        // // $products->transform(function ($product) {
        // //     $product->photo = $product->photo ? url('storage/' . $product->photo) : null;
        // //     $product->photo1 = $product->photo1 ? url('storage/' . $product->photo1) : null;
        // //     return $product;
        // // });

        // return response()->json($productData    , 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'size' => 'nullable|max:255',
            'photo' => 'required|image|max:2048',
            'photo1' => 'nullable|image|max:2048',
            'description' => 'nullable',
            'information' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        $validatedData = $validator->validated();
        
        
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $extension = $photo->getClientOriginalExtension();
            $filename = $request->input('name') . '.' . $extension;
            $path = Storage::disk('public')->putFileAs('photos', $photo, $filename);
            $validatedData['photo'] = $path;
        }
        
        if ($request->hasFile('photo1')) {
            $photo1 = $request->file('photo1');
            $extension = $photo1->getClientOriginalExtension();
            $filename = $request->input('name') . 'photo2.' . $extension;
            $path = Storage::disk('public')->putFileAs('photos', $photo1, $filename);
            $validatedData['photo1'] = $path;
        }
        
        $product = Product::create($validatedData);
        
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // $product = Product::with('sizes')->findOrFail($id);
        
            // // Transform the product to include image URLs
            // $product->photo = $product->photo ? url('storage/' . $product->photo) : null;
            // $product->photo1 = $product->photo1 ? url('storage/' . $product->photo1) : null;

            // $products = Product::all();
            // $productCollection = ProductResource::collection($products);
    
            // return response()->json($productCollection, 200);
            $products = Product::findOrFail($id);
            $product  = new ProductResource($products);
            return response()->json($product, 200);
        
            return response()->json($product, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
    }

    public function related($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Retrieve products belonging to the category
            $products = $category->products;
        
            // Transform the products to include image URLs
            $transformedProducts = $products->map(function ($product) {
                $product->photo = $product->photo ? url('storage/' . $product->photo) : null;
                $product->photo1 = $product->photo1 ? url('storage/' . $product->photo1) : null;
                return $product;
            });
        
            return response()->json($transformedProducts, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
    
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:categories|max:50|min:5',
                // Add any other validation rules for the category fields
            ]);
    
            // if ($validator->fails()) {
            //     return response()->json(['error' => $validator->errors()], 400);
            // }
    
            // Update the category
            $product->update($request->all());
    
            return response()->json(['message' => 'product updated successfully', 'product' => $product], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'product not found'], 404);
        }
    
        return response()->json(['message' => 'product deleted successfully'], 200);
    }
}
