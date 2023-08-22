<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $category = new Category([
            'name' => $request->input('name'),
            'background_color' => $request->input('background_color'),
            'text_color' => $request->input('text_color'),
        ]);
        $category->save();

        return response()->json(['message' => 'Category created successfully', 'category' => $category]);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'background_color' => 'required|string|max:7', 
            'text_color' => 'required|string|max:7',
        ]);

        $category->update($request->all());

        return response()->json([
            'message' => 'Categoria aggiornata con successo.',
            'data' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Categoria eliminata con successo.',
        ]);
    }
}
