<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->get();
        return response()->json(['data'=>$products], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try{
            
            $products = new Product();
            $products->title = $request->title;
            $products->price = $request->price;
            $products->description = $request->description;
            $products->discountpercentage = $request->discountpercentage;
            $products->rating = $request->rating;
            $products->quantity = $request->quantity;
            $products->brand = $request->brand;
            $products->category = $request->category;
            if($request->hasFile('image')){
                
                $imagepath = $request->file('image')->store('public');
                $imageURL = Storage::url($imagepath);
                $products->image = $imageURL;
            }
                $products->save();

                return response()->json(['message'=>'Product added successfully!'], 201);

        }catch(\Exception $e){
            return response()->json(['message'=>'error occured', 'errors'=>$e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $product = Product::findorFail($id);
            return response()->json(['data'=>$product], 200);
        }catch(\Exception $e){
            return response()->json(['message'=>'Product not Found!', 'error'=>$e->getMessage()],400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        try{
            $product = Product::findorFail($id);
            $product->title = $request->title;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->discountpercentage = $request->discountpercentage;
            $product->rating = $request->rating;
            $product->quantity = $request->quantity;
            $product->brand = $request->brand;
            $product->category = $request->category;
            if($request->hasFile('image')){
                
                $imagepath = $request->file('image')->store('products');
                
                $imageURL = Storage::url($imagepath);
                
                $product->image = $imageURL;
            }
                $product->save();

                return response()->json(['message'=>'Product updated successfully!'], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred!', 'errors' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            if ($product->image) {
                $imagePath = basename($product->image);
                Storage::delete('products/' . $imagePath);
            }

            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete product', 'error' => $e->getMessage()], 500);
        }
    }
}
