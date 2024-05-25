<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  // Tous les produits
  public function AllProduct()
  {
    return response()->json(['data' => Product::all()]);
  }

  // Produits en promotion
  public function PromotionalProducts()
  {
    $products = Product::where('promotion', '>=', 30)
      ->where('promotion', '<=', 50)
      ->get();
    return response()->json(['data' => $products]);
  }

  // Affichage d'un produit par ID ou nom
  public function show($param)
  {
    $product = is_numeric($param) ? Product::find($param) : Product::where('name', $param)->firstOrFail();
    return response()->json(['data' => $product]);
  }

  // Ajouter un produit
  public function AddProduct(Request $req)
{
    $validatedData = $req->validate([
        'name' => 'required',
        'desc' => 'required',
        'price' => 'required|numeric',
        'img' => 'required|image',
        'category' => 'required',
        'promotion' => 'nullable|integer' // Ajout du champ promotion
    ]);

    $product = new Product();
    $product->name = $validatedData['name'];
    $product->desc = $validatedData['desc'];
    $product->price = $validatedData['price'];
    $product->category = $validatedData['category'];
    $product->promotion = $validatedData['promotion']; // Assignation du champ promotion

    $imageName = time() . '.' . $req->file('img')->getClientOriginalExtension();
    $req->file('img')->move(public_path('products/'), $imageName);
    $product->img = $imageName;

    $product->save();
    return response()->json(['message' => 'Product has been added', 'data' => $product]);
}


  // Supprimer un produit
  public function DeleteProduct($id)
  {
    $result = Product::where('id', $id)->delete();
    if ($result) {
      return response()->json(['message' => 'Product has been deleted']);
    }
  }

  // Éditer un produit
  public function EditProduct($id)
  {
    $product = Product::findOrFail($id);
    return response()->json(['data' => $product]);
  }

  // Mettre à jour un produit
  public function UpdateProduct(Request $request, $id)
  {
    $product = Product::findOrFail($id);

    $validatedData = $request->validate([
      'name' => 'required',
      'desc' => 'required',
      'price' => 'required|numeric',
      'img' => 'required|image',
      'category' => 'required',
    ]);

    $product->name = $validatedData['name'];
    $product->desc = $validatedData['desc'];
    $product->price = $validatedData['price'];
    $product->category = $validatedData['category'];

    $imageName = time() . '.' . $request->file('img')->getClientOriginalExtension();
    $request->file('img')->move(public_path('products/'), $imageName);
    $product->img = $imageName;

    $product->save();

    return response()->json(['message' => 'Product has been updated']);
  }
  public function ProductCount()
{
    $productCount = Product::count();
    return response()->json(['count' => $productCount]);
}

}
