<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Models\Box;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = auth()->user()->reviews()
            ->with('box')
            ->paginate(10);

        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('reviews.edit');
    }

    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        return view('reviews.edit', compact('review'));
    }

    public function store(StoreReviewRequest $request)
    {
        $box = Box::findOrFail($request->box_id);

        $user = auth()->user();

        $hasSubscribed = $user->subscriptions()
            ->where('box_id', $box->id)
            ->exists();

        if (!$hasSubscribed) {
            return redirect()->back()->with('error', 'Vous devez avoir un abonnement pour cette box.');
        }

        $review = Review::updateOrCreate(
            [
                'user_id' => $user->id,
                'box_id' => $box->id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'pros' => $request->pros,
                'cons' => $request->cons,
                'would_recommend' => (bool)$request->would_recommend,
            ]
        );

        return redirect()->route('reviews.show', $review)->with('success', 'Avis créé avec succès.');
    }

    public function update(Review $review, StoreReviewRequest $request)
    {
        $this->authorize('update', $review);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'pros' => $request->pros,
            'cons' => $request->cons,
            'would_recommend' => (bool)$request->would_recommend,
        ]);

        return redirect()->route('reviews.show', $review)->with('success', 'Avis mis à jour avec succès.');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return response()->json(['message' => 'Avis supprimé avec succès.']);
    }

    public function markHelpful(Review $review)
    {
        $review->increment('helpful_count');

        return response()->json([
            'message' => 'Merci pour votre feedback.',
            'helpful_count' => $review->helpful_count,
        ]);
    }
}
