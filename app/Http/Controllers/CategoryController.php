<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // get all category 
    public function AllCategory()
    {
        $Categories = Category::all();
        return $Categories;
    }

    public function show($id)
    {
        $Categories = Category::find($id);
        return $Categories;
    }

    // save category 
    public function AddCategory(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|unique:categories',
            'img' => 'required|image',
        ]);

        $Category = new Category();
        $Category->name = $validatedData['name'];

        $imageName = time() . '.' . $request->file('img')->getClientOriginalExtension();
        $request->file('img')->move(public_path('categories/'), $imageName);
        $Category->img = $imageName;

        $Category->save();

        return response()->json(["message" => true, "data" => $Category]);
    }

    // dalete Category
    public function DeleteCategory($id)
    {

        $result = Category::where('id', $id)->delete();
        if ($result) {
            return 'is deleted';
        }
    }
}
