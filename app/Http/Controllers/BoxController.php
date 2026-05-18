<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Category;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function index(Request $request)
    {
        $query = Box::where('is_active', true)
            ->with('category', 'reviews')
            ->paginate(12);

        if ($request->category) {
            $query = Box::where('is_active', true)
                ->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                })
                ->with('category', 'reviews')
                ->paginate(12);
        }

        if ($request->search) {
            $query = Box::where('is_active', true)
                ->where(function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%");
                })
                ->with('category', 'reviews')
                ->paginate(12);
        }

        $categories = Category::all();

        return view('catalog.index', [
            'boxes' => $query,
            'categories' => $categories,
        ]);
    }

    public function show(Box $box)
    {
        if (!$box->is_active) {
            abort(404);
        }

        $box->load('category', 'reviews.user', 'subscriptions');

        return view('catalog.show', compact('box'));
    }

    public function catalog()
    {
        $boxes = Box::where('is_active', true)
            ->with('category', 'reviews')
            ->inRandomOrder()
            ->paginate(12);

        $categories = Category::all();

        return view('dashboard', [
            'boxes' => $boxes,
            'categories' => $categories,
        ]);
    }
}
