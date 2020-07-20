<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceCollection;
use Illuminate\Http\Request;
use App\Category;
use App\Product;

class CategoryController extends Controller
{
   public function create(array $data){
        return Category::create([
            'name' => $data['name'],
        ]);
    }
    

    public function index(): CategoryResourceCollection{
        return new CategoryResourceCollection(Category::paginate());
    }

    public function show(Category $category): CategoryResource {
        $product_id = Product::where('category', $category->name)->get();
        return new CategoryResource(array_merge([
            'Category' => $category,
            'Products' => $product_id,
        ]));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
        ]);
        $category = $this->create($request->all());
        return new CategoryResource($category);
    }

    public function update(Category $category, Request $request): CategoryResource{
        $category->update($request->all());
        return new CategoryResource($category);
    }

    public function destroy(Category $category){
        $category->delete();
        return response()->json(["data" => "Category deleted"], 200);
    }
}
