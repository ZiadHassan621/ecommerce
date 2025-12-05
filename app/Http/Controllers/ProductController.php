<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        if($products->isEmpty()){
            return response()->json(['message'=>'No products yet'],200);
        }
        return response()->json($products,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'description'=>'nullable|string',
            'price'=>'required|numeric',
            'stock'=>'required|integer|min:0',
        ]);
        $product = Product::create($data);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'No product with that id'], 404);
        }
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);
        $product->update($data);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product){
            return response()->json(['message'=>'No product with that id'],404);
        }

        $product->delete();

        return response()->json(["message" => "Product Deleted Successfully"], 200);
    }
}
