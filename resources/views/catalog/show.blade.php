@extends('layouts.app')

@section('title', 'Détails Box - LynBox')

@section('content')
<div class="space-y-8">
    <a href="{{ route('catalog.index') }}" class="flex items-center text-indigo-400 hover:text-indigo-300 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour au catalogue
    </a>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <div class="glass p-8 rounded-2xl">
                <div class="relative h-80 bg-gradient-to-br from-indigo-400 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-9xl mb-8 overflow-hidden">
                    @if($box->image_url)
                        <img src="{{ $box->image_url }}" alt="{{ $box->title }}" class="w-full h-full object-cover">
                    @else
                        {{ $box->emoji ?? '📦' }}
                    @endif
                </div>

                <div class="mb-8">
                    <p class="text-indigo-400 font-semibold uppercase text-sm mb-2">{{ $box->category->name }}</p>
                    <h1 class="text-4xl font-bold mb-4">{{ $box->title }}</h1>
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex items-center space-x-1">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star {{ $i < intval($box->averageRating()) ? 'text-yellow-400' : 'text-slate-600' }}"></i>
                            @endfor
                        </div>
                        <span class="text-slate-400">({{ $box->reviewCount() }} avis)</span>
                    </div>

                    <p class="text-slate-300 leading-relaxed text-lg">{{ $box->description }}</p>
                </div>

                <div class="border-t border-white/10 pt-8">
                    <h2 class="text-2xl font-bold mb-6">Avis des clients</h2>
                    @if($box->reviews->isEmpty())
                        <p class="text-slate-400">Aucun avis pour le moment.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($box->reviews as $review)
                                <div class="bg-white/5 p-4 rounded-lg">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold">{{ $review->user->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $review->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div class="flex space-x-1">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fas fa-star {{ $i < $review->rating ? 'text-yellow-400' : 'text-slate-600' }} text-sm"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-slate-300 text-sm">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass p-8 rounded-2xl sticky top-8">
                <div class="mb-8">
                    <span class="text-5xl font-bold text-indigo-400">{{ number_format($box->price, 2, ',', ' ') }}€</span>
                    <p class="text-slate-400 text-sm mt-2">
                        @if($box->billing_cycle === 'monthly')
                            Par mois
                        @elseif($box->billing_cycle === 'quarterly')
                            Par trimestre
                        @else
                            Par an
                        @endif
                    </p>
                </div>

                <div class="space-y-3 mb-8">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check text-green-400"></i>
                        <span class="text-sm">{{ $box->stock_quantity }} box en stock</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-truck text-indigo-400"></i>
                        <span class="text-sm">Livraison en 7 jours</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-undo text-blue-400"></i>
                        <span class="text-sm">Pause ou annulation gratuite</span>
                    </div>
                </div>

                @auth
                    @if($box->isInStock())
                        <button onclick="subscribeToBox({{ $box->id }})" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-bold transition shadow-lg shadow-indigo-600/30 mb-3">
                            S'abonner maintenant
                        </button>
                        @if(auth()->user()->subscriptions()->where('box_id', $box->id)->where('status', 'active')->exists())
                            <p class="text-green-400 text-xs text-center">✓ Vous êtes abonné à cette box</p>
                        @endif
                    @else
                        <button disabled class="w-full py-3 bg-slate-600 rounded-xl font-bold text-slate-400 cursor-not-allowed">
                            Rupture de stock
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-bold transition">
                        Connexion requise
                    </a>
                @endauth

                <div class="mt-6 pt-6 border-t border-white/10">
                    <h3 class="font-semibold mb-4">À propos de cette box</h3>
                    <div class="space-y-3 text-sm text-slate-400">
                        <div>
                            <p class="font-semibold text-white">Contenu</p>
                            <p>Produits sélectionnés de la catégorie {{ $box->category->name }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Renouvellement</p>
                            <p>
                                @if($box->billing_cycle === 'monthly')
                                    Chaque mois
                                @elseif($box->billing_cycle === 'quarterly')
                                    Tous les 3 mois
                                @else
                                    Chaque année
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Engagement</p>
                            <p>Sans engagement - annulation à tout moment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function subscribeToBox(boxId) {
    if (confirm('Vous abonner à cette box?')) {
        fetch('{{ route("subscriptions.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ box_id: boxId })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            window.location.href = '{{ route("subscriptions.index") }}';
        })
        .catch(error => {
            alert('Une erreur s\'est produite. Veuillez réessayer.');
            console.error('Error:', error);
        });
    }
}
</script>
@endsection
