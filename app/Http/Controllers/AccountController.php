<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Afficher la page des paramètres
     */
    public function settings(): View
    {
        return view('account.settings');
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        auth()->user()->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()->route('account.settings')->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Mettre à jour l'email
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'required|current_password',
        ]);

        auth()->user()->update(['email' => $validated['email']]);

        return redirect()->route('account.settings')->with('success', 'Email mis à jour avec succès');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account.settings')->with('success', 'Mot de passe mis à jour avec succès');
    }

    /**
     * Mettre à jour les préférences de confidentialité
     */
    public function updatePrivacy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'profile_public' => 'boolean',
            'show_reviews' => 'boolean',
            'show_wishlist' => 'boolean',
        ]);

        auth()->user()->update([
            'profile_public' => $validated['profile_public'] ?? false,
            'show_reviews' => $validated['show_reviews'] ?? false,
            'show_wishlist' => $validated['show_wishlist'] ?? false,
        ]);

        return redirect()->route('account.settings')->with('success', 'Paramètres de confidentialité mis à jour');
    }

    /**
     * Mettre à jour les préférences de notification
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'notify_orders' => 'boolean',
            'notify_shipping' => 'boolean',
            'notify_promotions' => 'boolean',
            'notify_sms_shipping' => 'boolean',
        ]);

        auth()->user()->update([
            'notify_orders' => $validated['notify_orders'] ?? true,
            'notify_shipping' => $validated['notify_shipping'] ?? true,
            'notify_promotions' => $validated['notify_promotions'] ?? false,
            'notify_sms_shipping' => $validated['notify_sms_shipping'] ?? false,
        ]);

        return redirect()->route('account.settings')->with('success', 'Préférences de notification mises à jour');
    }

    /**
     * Afficher les sessions actives
     */
    public function sessions(): View
    {
        return view('account.sessions');
    }

    /**
     * Supprimer le compte
     */
    public function delete(Request $request)
    {
        $user = auth()->user();
        
        // Supprimer toutes les données de l'utilisateur
        $user->subscriptions()->delete();
        $user->deliveryAddresses()->delete();
        $user->invoices()->delete();
        $user->reviews()->delete();
        $user->loyaltyPoints()->delete();
        
        // Supprimer l'utilisateur
        $user->delete();
        
        auth()->logout();
        
        return response()->json(['message' => 'Compte supprimé avec succès'], 200);
    }
}
