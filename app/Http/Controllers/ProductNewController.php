<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductNewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::orderBy("id", "desc")->get();
            return response()->json(['data' => $products], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'price' => 'required',
                'quantity' => 'required',
            ],
            [],
            ['name' => 'Name', 'price' => 'Price', 'quantity' => 'Quantity']
        );
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["status" => false, "messages" => $messages], 500);
        }
        try {
            // $product = Product::create($request->all());
            $product = new Product;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;

            if ($request->hasFile("file")) {
                $image = $request->file("file");
                $destinationPath = public_path('uploads');
                $image->move($destinationPath, $image->getClientOriginalName());
                $product->image = 'uploads/' . $image->getClientOriginalName();
            }
            $product->save();
            // return "done";
            return response()->json(["status" => true, "data" => $product], 200);
        } catch (\Exception $e) {
            return response()->json(["status" => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json([
                'data' => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'price' => 'required',
                'quantity' => 'required',
            ],
            [],
            ['name' => 'Name', 'price' => 'Price', 'quantity' => 'Quantity']
        );
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["status" => false, "messages" => $messages], 500);
        }
        try {
            $product = Product::find($id);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            if ($request->hasFile("file")) {
                $image = $request->file("file");
                $destinationPath = public_path('uploads');
                $image->move($destinationPath, $image->getClientOriginalName());
                $product->image = 'uploads/' . $image->getClientOriginalName();
            }
            $product->update();
            return response()->json(["status" => true, "message" => "Product Updated", "data" => $product], 200);
        } catch (\Exception $e) {
            return response()->json(["status" => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::find($id);
            $product->delete();
            return response()->json(['status' => true, 'message' => 'Deleted Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
