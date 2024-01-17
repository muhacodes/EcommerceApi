<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderProductResource;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        
        // $orders = OrderProduct::with('product')->get();

        // return response()->json($orders, 200);

        // Full details of orderproducts and each products variations, can be customized though. 
        $orders = OrderProductResource::collection(OrderProduct::with('product')->get());
        return response()->json($orders, 200);

        // $orders = OrderResource::collection(Order::with('orderProducts')->get()); // Assuming you have a relationship named orderProducts
        // return response()->json($orders);

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
            'name' => 'required|max:255',
            'address' => 'required|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'required|max:255',
            'products' => 'required|array', // Ensure products is an array
            'products.*' => 'required|array', // Each product item must be an array
            'products.*.id' => 'required|exists:products,id', // Validate product_id within each product
            'products.*.quantity' => 'required|integer|min:1', // Validate quantity  // Validate each product_id
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        

        $order = new Order;
        $order->name = $request->input('name');
        $order->address = $request->input('address');
        $order->email = $request->input('email');
        $order->tel = $request->input('tel');
        $quantity = $request->input('quantity');
        $order->save();
        
        $products = $request->input('products');
        $orderProducts = [];
        
        foreach ($products as $productData) {
            $product = Product::find($productData['id']);
            if (!$product) {
                return response()->json([
                    'errors' => 'Invalid product ID: ' . $productData['id']
                ], 422);
            }
        
            $orderProduct = new OrderProduct;
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->quantity = $productData['quantity']; // Assuming a quantity of 1 for each product
            $orderProduct->color = $productData['color'];
            $orderProduct->size = $productData['size'];
            $orderProduct->save();
            $orderProducts[] = $orderProduct;
        }
        
        // $order->load('orderProducts');
        
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ], 201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'order not found'], 404);
        }
    
        return response()->json(['message' => 'order deleted successfully'], 200);
    }
}
