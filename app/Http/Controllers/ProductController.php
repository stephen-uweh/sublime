<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductResourceCollection;
use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    public function create(array $data){
        return Product::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'category' => $data['category'],
        ]);
    }

    public function index(): ProductResourceCollection{
        return new ProductResourceCollection(Product::paginate());
    }

    public function show(Product $product): ProductResource{
        return new ProductResource($product);
    }

    public function store(){
        $entry = request()->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'category' => '',
        ]);
        $category_name = Category::where('id', request('category_id'))->first();
        $category_id = $category_name->name;
        $category_array = ['category' => $category_id];
        $final_entry = array_merge(
            $entry,
            $category_array,
        );
        $category = $this->create($final_entry);
        return new ProductResource($category);
    }

    public function update(Product $product, Request $request): ProductResource{
        $entry = request()->validate([
            'category_id' => '',
            'name' => '',
            'price' => '',
            'description' => '',
            'category' => '',
        ]);
        if (request('category')){
            $category_name = Category::where('name', request('category'))->first();
            $category_id = $category_name->id;
            $category_array = ['category_id' => $category_id]; 
        }
        $final_entry = array_merge(
            $entry,
            $category_array ?? []
        );
        $product->update($final_entry);
        return new ProductResource($product);
    }

    public function destroy(Product $product){
        $product->delete();
        echo('Success');
        return response()->json(["data" => "Product deleted"], 200);
    }
}
