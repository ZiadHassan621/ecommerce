<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Product;
use Illuminate\Http\Request;
use Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                return Order::with('items.product','user')->latest()->paginate(20);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address'=>'required|string',
            'phone'=>'required|string',
            'items'=>'required|array|min:1',
            'items.*.product_id'=>'required|integer|distinct',
            'items.*.quantity'=>'required|integer|min:1'
        ]);
        
        $items = $request->input('items');
        $total = 0;
        foreach($items as $item){
            $product = Product::find($item['product_id']);
            if(!$product){
                return response(['error' => 'Product Not Found'], 404);
            }
            if($product->stock < $item['quantity']){
               return response()->json(['error' => "Insufficient stock for {$product->name}"], 400);

            }
            $total += $product->price * $item['quantity'];

        }
     // dd(strtoupper(Str::random(10)));
         $order = Order::create([
        'order_number' => strtoupper(Str::random(10)),
        'user_id' => auth()->id(),
        'address' => $request->address,
        'phone' => $request->phone,
        'total' => $total,
    ]);

   // dd("ziad");

    foreach($items as $item){
        $product = Product::find($item['product_id']);
            if(!$product){
                return response(['error' => 'Product Not Found'], 404);
            }

              $updated = Product::where('id', $item['product_id'])
                          ->where('stock', '>=', $item['quantity'])
                          ->decrement('stock', $item['quantity']);

        if (!$updated) {
            return response()->json(['error' => "Insufficient stock for {$product->name}"], 400);
        }
       Orderitem::create([
             'quantity' => $item['quantity'],
            'price' => $product->price,
            'order_id' => $order->id,
            'product_id' => $product->id
           
        ]);
                 

        $product->refresh();
        if ($product->stock <= 0) {
            $product->status = 'out_of_stock';
            $product->save();
        }
    }

     $itemsSummary = $order->items()->with('product')->get()->map(function($it){
        return [
            'product_id' => $it->product_id,
            'name' => $it->product->name,
            'quantity' => $it->quantity,
            'price' => $it->price
        ];
    });

    return response()->json([
        'order_number' => $order->order_number,
        'total' => $order->total,
        'items' => $itemsSummary
    ], 201);
        

        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
                return Order::with('items.product','user')->findOrFail($id);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
