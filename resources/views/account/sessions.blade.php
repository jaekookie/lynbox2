@extends('layouts.app')

@section('title', 'Sessions Actives - LynBox')

@section('content')
<div class="space-y-8">
    <a href="{{ route('account.settings') }}" class="flex items-center text-indigo-400 hover:text-indigo-300 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux paramètres
    </a>

    <div>
        <h1 class="text-3xl font-bold mb-2">🔐 Sessions Actives</h1>
        <p class="text-slate-400">Gérez vos connexions actives</p>
    </div>

    <!-- Session Actuelle -->
    <div class="glass p-6">
        <h2 class="font-bold text-lg mb-4">Session Actuelle</h2>
        <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-desktop text-green-400 text-lg"></i>
                        <p class="font-bold">Navigation Web</p>
                        <span class="text-xs bg-green-500/20 text-green-400 px-2 py-1 rounded">Active</span>
                    </div>
                    <p class="text-xs text-slate-400 ml-6">
                        {{ request()->header('User-Agent') ? Str::limit(request()->header('User-Agent'), 50) : 'Navigateur inconnu' }}
                    </p>
                    <p class="text-xs text-slate-400 ml-6">
                        IP: {{ request()->ip() }}
                    </p>
                    <p class="text-xs text-green-400 ml-6 mt-1">
                        Connecté depuis {{ now()->diffForHumans() }}
                    </p>
                </div>
                <button disabled class="px-4 py-2 bg-slate-600/20 text-slate-400 rounded text-sm cursor-not-allowed">
                    <i class="fas fa-lock mr-1"></i> Actuelle
                </button>
            </div>
        </div>
    </div>

    <!-- Autres Sessions -->
    <div class="glass p-6">
        <h2 class="font-bold text-lg mb-4">Autres Sessions</h2>
        <div class="p-6 text-center text-slate-400">
            <i class="fas fa-check-circle text-green-400 text-2xl mb-2 block"></i>
            <p>Vous n'avez qu'une seule session active</p>
        </div>
    </div>

    <!-- Déconnecter partout -->
    <div class="glass p-6 border border-red-500/20">
        <h2 class="font-bold text-lg mb-4 text-red-400">⚠️ Actions Dangereuses</h2>
        
        <div class="space-y-3">
            <p class="text-sm text-slate-300">
                Déconnectez-vous de toutes les autres sessions. Cette action ne déconnectera pas votre session actuelle.
            </p>
            <form action="{{ route('account.logout-other-sessions') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded-lg font-semibold transition" 
                    onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter de toutes les autres sessions?')">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Déconnecter des autres sessions
                </button>
            </form>
        </div>
    </div>

    <!-- Informations de Sécurité -->
    <div class="glass p-6 border border-blue-400/20 bg-blue-500/5">
        <h3 class="font-bold mb-3">
            <i class="fas fa-shield-alt text-blue-400 mr-2"></i>
            Conseils de Sécurité
        </h3>
        <ul class="text-sm text-slate-300 space-y-2">
            <li><i class="fas fa-check text-green-400 mr-2"></i> Déconnectez-vous des sessions que vous ne reconnaissez pas</li>
            <li><i class="fas fa-check text-green-400 mr-2"></i> Changez votre mot de passe régulièrement</li>
            <li><i class="fas fa-check text-green-400 mr-2"></i> Ne partagez jamais votre mot de passe</li>
            <li><i class="fas fa-check text-green-400 mr-2"></i> Utilisez une connexion sécurisée (HTTPS)</li>
        </ul>
    </div>
</div>
@endsection
