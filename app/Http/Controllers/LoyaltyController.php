<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoints;
use Illuminate\View\View;

class LoyaltyController extends Controller
{
    /**
     * Afficher la page de fidélité
     */
    public function index(): View
    {
        $loyaltyPoints = auth()->user()->loyaltyPoints;

        return view('loyalty.index', compact('loyaltyPoints'));
    }
}
