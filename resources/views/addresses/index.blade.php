@extends('layouts.app')

@section('title', 'Adresses de Livraison - LynBox')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Adresses de Livraison</h1>
            <p class="text-slate-400">Gérez vos adresses de livraison</p>
        </div>
        <a href="{{ route('addresses.create') }}" class="py-2 px-4 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold transition">
            <i class="fas fa-plus mr-2"></i> Ajouter une adresse
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="glass p-12 text-center">
            <i class="fas fa-map-marker-alt text-4xl text-slate-500 mb-4 block"></i>
            <p class="text-slate-400 mb-4">Aucune adresse enregistrée</p>
            <a href="{{ route('addresses.create') }}" class="inline-block py-2 px-4 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold transition">
                Ajouter votre première adresse
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($addresses as $address)
                <div class="glass p-6 relative group">
                    <!-- Badge Default -->
                    @if($address->is_default)
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-500/20 text-green-400 border border-green-500/30 text-xs px-2 py-1 rounded">
                                <i class="fas fa-check-circle mr-1"></i> Par défaut
                            </span>
                        </div>
                    @endif

                    <!-- Contenu -->
                    <div class="pr-20 mb-6">
                        <h3 class="font-bold text-lg mb-2">
                            {{ $address->address_type === 'home' ? '🏠 Domicile' : '💼 Bureau' }}
                        </h3>
                        <p class="font-semibold text-white">{{ $address->full_name }}</p>
                    </div>

                    <!-- Adresse -->
                    <div class="space-y-2 text-sm text-slate-300 mb-6 pb-6 border-b border-white/10">
                        <p>{{ $address->street_address }}</p>
                        <p>{{ $address->postal_code }} {{ $address->city }}</p>
                        <p>{{ $address->country }}</p>
                        @if($address->phone)
                            <p class="text-slate-400 mt-2">📱 {{ $address->phone }}</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('addresses.edit', $address) }}" class="flex-1 py-2 text-center bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 rounded text-sm font-semibold transition">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
                        @if(!$address->is_default)
                            <form action="{{ route('addresses.default', $address) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-white/5 hover:bg-white/10 rounded text-sm transition">
                                    <i class="fas fa-check mr-1"></i> Par défaut
                                </button>
                            </form>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr?')" class="w-full py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded text-sm transition">
                                    <i class="fas fa-trash mr-1"></i> Supprimer
                                </button>
                            </form>
                        @else
                            <button disabled class="flex-1 py-2 bg-slate-600/20 text-slate-500 rounded text-sm" title="Adresse par défaut">
                                <i class="fas fa-lock mr-1"></i> Défaut
                            </button>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Cette adresse est utilisée. Êtes-vous sûr de la supprimer?')" class="w-full py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded text-sm transition">
                                    <i class="fas fa-trash mr-1"></i> Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Conseil -->
    <div class="glass p-6 border border-indigo-400/20 bg-indigo-500/5">
        <p class="text-sm text-slate-300">
            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
            <strong>Conseil:</strong> Vous pouvez ajouter plusieurs adresses et choisir celle de livraison à chaque renouvellement
        </p>
    </div>
</div>
@endsection
