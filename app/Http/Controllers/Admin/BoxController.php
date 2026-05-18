<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Box;
use App\Models\Category;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        $boxes = Box::with('category')->paginate(20);
        return view('admin.boxes.index', compact('boxes'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $categories = Category::all();
        return view('admin.boxes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'stock_quantity' => 'required|integer|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'is_active' => 'boolean',
        ]);

        Box::create($validated);

        return redirect()->route('admin.boxes.index')->with('success', 'Box créée avec succès.');
    }

    public function edit(Box $box)
    {
        $this->authorizeAdmin();
        $categories = Category::all();
        return view('admin.boxes.edit', compact('box', 'categories'));
    }

    public function update(Box $box, Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'stock_quantity' => 'required|integer|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'is_active' => 'boolean',
        ]);

        $box->update($validated);

        return redirect()->route('admin.boxes.index')->with('success', 'Box mise à jour avec succès.');
    }

    public function destroy(Box $box)
    {
        $this->authorizeAdmin();
        $box->delete();
        return redirect()->route('admin.boxes.index')->with('success', 'Box supprimée avec succès.');
    }

    private function authorizeAdmin()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
    }
}
