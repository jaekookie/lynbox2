@extends('layouts.public')

@section('title', 'Connexion - LynBox')

@section('content')
<div class="flex items-center justify-center min-h-screen py-12">
    <div class="w-full max-w-md">
        <div class="glass p-8 relative overflow-hidden">
            <!-- Décoration de fond -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-purple-500/10 rounded-full blur-3xl -ml-20 -mb-20"></div>

            <div class="relative z-10">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-box-open text-indigo-400 mr-2"></i>
                        Lyn<span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-600">Box</span>
                    </h1>
                    <p class="text-slate-400">Plateforme de gestion d'abonnements premium 📦</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-400 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold mb-2 text-slate-300">Email *</label>
                        <input type="email" name="email" placeholder="demo@lynbox.com" value="{{ old('email') }}"
                            class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-600 p-3 focus:border-indigo-500 focus:outline-none transition"
                            required>
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2 text-slate-300">Mot de passe *</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-600 p-3 focus:border-indigo-500 focus:outline-none transition"
                            required>
                        @error('password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded accent-indigo-500">
                        <label for="remember" class="text-sm text-slate-400">Se souvenir de moi</label>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 rounded-lg font-semibold transition shadow-lg shadow-indigo-500/20 mt-6">
                        <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                    </button>
                </form>

                <!-- Identifiants de test -->
                <div class="mt-8 pt-8 border-t border-white/10">
                    <p class="text-xs font-semibold text-slate-300 mb-4 uppercase tracking-wider">
                        <i class="fas fa-flask text-yellow-400 mr-2"></i> Comptes de Test
                    </p>
                    <div class="space-y-3">
                        <div class="p-3 rounded-lg bg-indigo-500/10 border border-indigo-500/20 hover:bg-indigo-500/20 transition cursor-pointer group"
                            onclick="populateForm('demo@lynbox.com', 'demo123456')">
                            <p class="font-mono text-sm text-indigo-400 group-hover:text-indigo-300">demo@lynbox.com</p>
                            <p class="text-xs text-slate-500">Demo Account (Standard)</p>
                        </div>
                        <div class="p-3 rounded-lg bg-purple-500/10 border border-purple-500/20 hover:bg-purple-500/20 transition cursor-pointer group"
                            onclick="populateForm('admin@lynbox.com', 'admin123')">
                            <p class="font-mono text-sm text-purple-400 group-hover:text-purple-300">admin@lynbox.com</p>
                            <p class="text-xs text-slate-500">Administrator Account</p>
                        </div>
                        <div class="p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/20 hover:bg-yellow-500/20 transition cursor-pointer group"
                            onclick="populateForm('premium@lynbox.com', 'test123456')">
                            <p class="font-mono text-sm text-yellow-400 group-hover:text-yellow-300">premium@lynbox.com</p>
                            <p class="text-xs text-slate-500">Premium Account</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-4 text-center">
                        Cliquez sur un compte pour le remplir automatiquement
                    </p>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-slate-400 text-sm">
                        Pas de compte?
                        <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Information supplémentaire -->
        <div class="text-center mt-8">
            <p class="text-slate-500 text-sm">
                LynBox: Plateforme de gestion d'abonnements et livraisons | Tous droits réservés
            </p>
        </div>
    </div>
</div>

<script>
function populateForm(email, password) {
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = password;
    // Optionnel: cocher "Se souvenir de moi"
    document.getElementById('remember').checked = true;
}
</script>
@endsection
