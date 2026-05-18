@extends('layouts.app')

@section('title', 'Paramètres du Compte - LynBox')

@section('content')
<div class="space-y-8">
    <header>
        <h1 class="text-4xl font-bold">Paramètres du Compte</h1>
        <p class="text-slate-400 mt-2">Gérez votre profil, sécurité et préférences</p>
    </header>

    <!-- Résumé du compte -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-6 rounded-2xl">
            <div class="flex items-center space-x-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=818cf8&color=fff" 
                     class="h-20 w-20 rounded-2xl" alt="Avatar">
                <div>
                    <p class="text-xs text-slate-500 uppercase">Statut</p>
                    <h3 class="text-xl font-bold">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-indigo-400 font-semibold">👑 {{ ucfirst(auth()->user()->membership_tier) }}</p>
                </div>
            </div>
        </div>
        
        <div class="glass p-6 rounded-2xl">
            <p class="text-xs text-slate-500 uppercase mb-2">Email</p>
            <p class="font-semibold truncate">{{ auth()->user()->email }}</p>
            <p class="text-xs text-slate-500 mt-3">✓ Vérifié {{ optional(auth()->user()->email_verified_at)->format('d M Y') }}</p>
        </div>

        <div class="glass p-6 rounded-2xl">
            <p class="text-xs text-slate-500 uppercase mb-2">Abonnements</p>
            <p class="text-2xl font-bold text-indigo-400">{{ auth()->user()->subscriptions()->where('status', 'active')->count() }}</p>
            <p class="text-xs text-slate-500 mt-3">actifs, {{ auth()->user()->subscriptions()->where('status', 'paused')->count() }} en pause</p>
        </div>
    </div>

    <!-- Sections principales -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- PROFIL -->
        <div class="glass p-8 rounded-2xl">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-10 w-10 bg-indigo-500/20 rounded-xl flex items-center justify-center text-lg">
                    👤
                </div>
                <h2 class="text-xl font-bold">Informations Personnelles</h2>
            </div>

            <form action="{{ route('account.update-profile') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="text-sm font-semibold block mb-2">Nom complet</label>
                    <input type="text" name="first_name" value="{{ auth()->user()->first_name ?? '' }}" placeholder="Votre prénom"
                        class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition" required>
                </div>

                <div>
                    <label class="text-sm font-semibold block mb-2">Téléphone</label>
                    <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="+33612345678"
                        class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition">
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </form>
        </div>

        <!-- SÉCURITÉ -->
        <div class="glass p-8 rounded-2xl">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-10 w-10 bg-green-500/20 rounded-xl flex items-center justify-center text-lg">
                    🔒
                </div>
                <h2 class="text-xl font-bold">Sécurité</h2>
            </div>

            <div class="space-y-4">
                <form action="{{ route('account.update-password') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="text-sm font-semibold block mb-2">Mot de passe actuel</label>
                        <input type="password" name="current_password" placeholder="••••••••"
                            class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition" required>
                    </div>

                    <div>
                        <label class="text-sm font-semibold block mb-2">Nouveau mot de passe</label>
                        <input type="password" name="password" placeholder="Minimum 8 caractères"
                            class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition" required>
                    </div>

                    <div>
                        <label class="text-sm font-semibold block mb-2">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                            class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition" required>
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                        <i class="fas fa-key mr-2"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>

        <!-- EMAIL -->
        <div class="glass p-8 rounded-2xl">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-10 w-10 bg-blue-500/20 rounded-xl flex items-center justify-center text-lg">
                    ✉️
                </div>
                <h2 class="text-xl font-bold">Adresse Email</h2>
            </div>

            <form action="{{ route('account.update-email') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="text-sm font-semibold block mb-2">Nouvel email</label>
                    <input type="email" name="email" placeholder="nouveau@exemple.com"
                        class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition" required>
                </div>

                <div>
                    <label class="text-sm font-semibold block mb-2">Confirmer votre mot de passe</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition" required>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                    <i class="fas fa-envelope mr-2"></i> Changer d'email
                </button>
            </form>
        </div>

        <!-- NOTIFICATIONS -->
        <div class="glass p-8 rounded-2xl">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-10 w-10 bg-yellow-500/20 rounded-xl flex items-center justify-center text-lg">
                    🔔
                </div>
                <h2 class="text-xl font-bold">Notifications</h2>
            </div>

            <form action="{{ route('account.update-notifications') }}" method="POST" class="space-y-3">
                @csrf
                
                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 cursor-pointer transition">
                    <input type="checkbox" name="notify_orders" class="w-5 h-5 rounded accent-indigo-500" checked>
                    <span class="text-sm font-semibold">Notifications de commande</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 cursor-pointer transition">
                    <input type="checkbox" name="notify_shipping" class="w-5 h-5 rounded accent-indigo-500" checked>
                    <span class="text-sm font-semibold">Mises à jour d'expédition</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 cursor-pointer transition">
                    <input type="checkbox" name="notify_promotions" class="w-5 h-5 rounded accent-indigo-500">
                    <span class="text-sm font-semibold">Offres promotionnelles</span>
                </label>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition mt-4">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </form>
        </div>

        <!-- CONFIDENTIALITÉ -->
        <div class="glass p-8 rounded-2xl">
            <div class="flex items-center space-x-3 mb-6">
                <div class="h-10 w-10 bg-purple-500/20 rounded-xl flex items-center justify-center text-lg">
                    🛡️
                </div>
                <h2 class="text-xl font-bold">Confidentialité</h2>
            </div>

            <form action="{{ route('account.update-privacy') }}" method="POST" class="space-y-3">
                @csrf
                
                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 cursor-pointer transition">
                    <input type="checkbox" name="profile_public" class="w-5 h-5 rounded accent-indigo-500" {{ auth()->user()->profile_public ? 'checked' : '' }}>
                    <span class="text-sm font-semibold">Profil public</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 cursor-pointer transition">
                    <input type="checkbox" name="show_reviews" class="w-5 h-5 rounded accent-indigo-500" {{ auth()->user()->show_reviews ? 'checked' : '' }}>
                    <span class="text-sm font-semibold">Afficher mes avis</span>
                </label>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition mt-4">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </form>
        </div>
    </div>

    <!-- Zone de danger -->
    <div class="glass p-8 rounded-2xl border border-red-500/20">
        <div class="flex items-center space-x-3 mb-6">
            <div class="h-10 w-10 bg-red-500/20 rounded-xl flex items-center justify-center text-lg">
                ⚠️
            </div>
            <h2 class="text-xl font-bold text-red-400">Zone de Danger</h2>
        </div>

        <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
            <p class="text-sm font-semibold text-red-400 mb-2">Supprimer définitivement votre compte</p>
            <p class="text-sm text-slate-300 mb-4">Cette action est <strong>irréversible</strong>. Toutes vos données seront supprimées.</p>
            <button onclick="confirmDelete()" class="px-6 py-3 bg-red-600 hover:bg-red-500 rounded-lg font-semibold transition">
                <i class="fas fa-trash mr-2"></i> Supprimer mon compte
            </button>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('⚠️ ATTENTION: Cette action est définitive. Êtes-vous absolument certain?')) {
        if (confirm('Veuillez confirmer une dernière fois. TOUTES vos données seront perdues.')) {
            fetch('{{ route("account.delete") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => window.location.href = '{{ route("home") }}');
        }
    }
}
</script>
@endsection
