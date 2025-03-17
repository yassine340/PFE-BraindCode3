<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    // Display a listing of the modules
    public function index()
    {
        $modules = Module::all();
        return response()->json($modules);
    }

    // Show the form for creating a new module
    public function create()
    {
        // return view('modules.create');
    }

    // Store a newly created module
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ordre' => 'required|integer',
            'duree_estimee' => 'required|integer',
        ]);

        $module = Module::create($request->all());

        return response()->json($module);
    }

    // Show the specified module
    public function show(Module $module)
    {
        return response()->json($module);
    }

    // Update the specified module
    public function update(Request $request, Module $module)
    {
        $module->update($request->all());
        return response()->json($module);
    }

    // Remove the specified module
    public function destroy(Module $module)
    {
        $module->delete();
        return response()->json(['message' => 'Module deleted']);
    }
}
