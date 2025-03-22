<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('categories/Index', [
            'categories' => Category::all()
        ]);
    }
    public function create()
    {
        return Inertia::render('categories/Create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($request->all());

        return redirect()->back();
    }
    public function edit($id)
{
    $category = Category::findOrFail($id);
    return Inertia::render('categories/Edit', [
        'category' => $category
    ]);
}

        public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->back();
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Catégorie supprimée avec succès']);
    }


}
