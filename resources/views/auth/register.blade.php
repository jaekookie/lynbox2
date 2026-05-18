@extends('layouts.public')

@section('title', 'S\'inscrire - LynBox')

@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <div class="glass p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">🎁 LynBox</h1>
                <p class="text-slate-400">Créez votre compte</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-400 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                    <input type="text" name="name" placeholder="Jean Dupont" value="{{ old('name') }}"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                        required>
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Email *</label>
                    <input type="email" name="email" placeholder="vous@example.com" value="{{ old('email') }}"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                        required>
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Mot de passe *</label>
                    <input type="password" name="password" placeholder="Minimum 8 caractères"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                        required>
                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                        required>
                </div>

                <label class="flex items-start gap-2">
                    <input type="checkbox" name="agree_terms" class="w-4 h-4 mt-1 rounded accent-indigo-500" required>
                    <span class="text-xs text-slate-400">
                        J'accepte les
                        <a href="#" class="text-indigo-400 hover:text-indigo-300">conditions d'utilisation</a>
                        et la
                        <a href="#" class="text-indigo-400 hover:text-indigo-300">politique de confidentialité</a>
                    </span>
                </label>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition mt-6">
                    S'inscrire
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-slate-400 text-sm">
                    Vous avez déjà un compte?
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                        Se connecter
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
