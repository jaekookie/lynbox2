@extends('layouts.app')

@section('title', $address ? 'Modifier Adresse' : 'Ajouter Adresse - LynBox')

@section('content')
<div class="space-y-8 max-w-2xl">
    <a href="{{ route('addresses.index') }}" class="flex items-center text-indigo-400 hover:text-indigo-300 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux adresses
    </a>

    <div>
        <h1 class="text-3xl font-bold mb-2">
            {{ $address ? '✏️ Modifier Adresse' : '➕ Ajouter une Adresse' }}
        </h1>
        <p class="text-slate-400">{{ $address ? 'Mettez à jour votre adresse de livraison' : 'Enregistrez une nouvelle adresse de livraison' }}</p>
    </div>

    <form action="{{ $address ? route('addresses.update', $address) : route('addresses.store') }}" method="POST" class="space-y-6">
        @csrf
        @if($address)
            @method('PATCH')
        @endif

        <!-- Type d'adresse -->
        <div class="glass p-6">
            <label class="block text-sm font-semibold mb-4">
                <i class="fas fa-tag mr-2 text-indigo-400"></i>
                Type d'adresse
            </label>
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center gap-3 p-3 rounded-lg border-2 {{ old('address_type', $address?->address_type ?? 'home') === 'home' ? 'border-indigo-500 bg-indigo-500/10' : 'border-white/10 hover:border-white/20' }} cursor-pointer transition">
                    <input type="radio" name="address_type" value="home" class="w-4 h-4 accent-indigo-500"
                        {{ old('address_type', $address?->address_type ?? 'home') === 'home' ? 'checked' : '' }}>
                    <span>
                        <i class="fas fa-home mr-2 text-amber-400"></i>
                        <strong>Domicile</strong>
                    </span>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-lg border-2 {{ old('address_type', $address?->address_type) === 'work' ? 'border-indigo-500 bg-indigo-500/10' : 'border-white/10 hover:border-white/20' }} cursor-pointer transition">
                    <input type="radio" name="address_type" value="work" class="w-4 h-4 accent-indigo-500"
                        {{ old('address_type', $address?->address_type) === 'work' ? 'checked' : '' }}>
                    <span>
                        <i class="fas fa-briefcase mr-2 text-blue-400"></i>
                        <strong>Bureau</strong>
                    </span>
                </label>
            </div>
            @error('address_type')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Informations personnelles -->
        <div class="glass p-6">
            <h3 class="font-bold mb-4">
                <i class="fas fa-user mr-2 text-indigo-400"></i>
                Informations personnelles
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Prénom *</label>
                    <input type="text" name="first_name" placeholder="Jean"
                        value="{{ old('first_name', $address?->first_name) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                        required>
                    @error('first_name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Nom *</label>
                    <input type="text" name="last_name" placeholder="Dupont"
                        value="{{ old('last_name', $address?->last_name) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                        required>
                    @error('last_name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-semibold mb-2">Téléphone</label>
                <input type="tel" name="phone" placeholder="+33612345678"
                    value="{{ old('phone', $address?->phone) }}"
                    class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition">
                @error('phone')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Adresse -->
        <div class="glass p-6">
            <h3 class="font-bold mb-4">
                <i class="fas fa-map-marker-alt mr-2 text-indigo-400"></i>
                Adresse complète
            </h3>
            <div>
                <label class="block text-sm font-semibold mb-2">Adresse *</label>
                <input type="text" name="street_address" placeholder="123 Rue de la Paix"
                    value="{{ old('street_address', $address?->street_address) }}"
                    class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                    required>
                @error('street_address')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Code Postal *</label>
                    <input type="text" name="postal_code" placeholder="75001"
                        value="{{ old('postal_code', $address?->postal_code) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                        required>
                    @error('postal_code')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Ville *</label>
                    <input type="text" name="city" placeholder="Paris"
                        value="{{ old('city', $address?->city) }}"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition"
                        required>
                    @error('city')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold mb-2">Pays *</label>
                <select name="country" class="w-full bg-white/5 border border-white/10 rounded-lg text-white p-3 focus:border-indigo-500 focus:outline-none transition" required>
                    <option>-- Sélectionner un pays --</option>
                    <option value="France" {{ old('country', $address?->country ?? 'France') === 'France' ? 'selected' : '' }}>France</option>
                    <option value="Belgique" {{ old('country', $address?->country) === 'Belgique' ? 'selected' : '' }}>Belgique</option>
                    <option value="Suisse" {{ old('country', $address?->country) === 'Suisse' ? 'selected' : '' }}>Suisse</option>
                    <option value="Luxembourg" {{ old('country', $address?->country) === 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                    <option value="Pays-Bas" {{ old('country', $address?->country) === 'Pays-Bas' ? 'selected' : '' }}>Pays-Bas</option>
                    <option value="Allemagne" {{ old('country', $address?->country) === 'Allemagne' ? 'selected' : '' }}>Allemagne</option>
                    <option value="Italie" {{ old('country', $address?->country) === 'Italie' ? 'selected' : '' }}>Italie</option>
                    <option value="Espagne" {{ old('country', $address?->country) === 'Espagne' ? 'selected' : '' }}>Espagne</option>
                </select>
                @error('country')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Utiliser comme adresse par défaut -->
        @if(!$address)
            <div class="glass p-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="set_as_default" class="w-4 h-4 rounded accent-indigo-500" checked>
                    <span class="font-semibold">
                        <i class="fas fa-star text-yellow-400 mr-2"></i>
                        Utiliser comme adresse par défaut
                    </span>
                </label>
            </div>
        @endif

        <!-- Assistance -->
        <div class="glass p-6 border border-blue-400/20 bg-blue-500/5">
            <p class="text-sm text-slate-300">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                <strong>Info:</strong> Vous pouvez modifier cette adresse à tout moment
            </p>
        </div>

        <!-- Boutons -->
        <div class="flex gap-4">
            <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                <i class="fas fa-save mr-2"></i>
                {{ $address ? 'Mettre à jour' : 'Ajouter l\'adresse' }}
            </button>
            <a href="{{ route('addresses.index') }}" class="flex-1 py-3 bg-white/5 hover:bg-white/10 rounded-lg font-semibold transition text-center">
                <i class="fas fa-times mr-2"></i> Annuler
            </a>
        </div>
    </form>
</div>
@endsection
