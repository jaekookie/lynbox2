@extends('layouts.app')

@section('title', 'Détails Avis - LynBox')

@section('content')
<div class="space-y-8">
    <a href="{{ route('reviews.index') }}" class="flex items-center text-indigo-400 hover:text-indigo-300 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux avis
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="glass p-8">
                <!-- Informations de l'avis -->
                <div class="flex items-start justify-between mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-xl font-bold">{{ substr($review->user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <p class="font-bold text-lg">{{ $review->user->name }}</p>
                                @if(auth()->id() === $review->user_id)
                                    <span class="text-xs bg-indigo-600/20 text-indigo-400 px-2 py-1 rounded">Votre avis</span>
                                @endif
                            </div>
                            <p class="text-slate-400 text-sm">{{ $review->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center justify-end gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-600' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-slate-400">{{ $review->rating }}/5</span>
                    </div>
                </div>

                <!-- Contenu de l'avis -->
                <div class="border-t border-white/10 pt-8 mb-8">
                    <h3 class="font-bold text-lg mb-4">Avis</h3>
                    <p class="text-slate-300 leading-relaxed">{{ $review->comment }}</p>
                </div>

                <!-- Points positifs et négatifs -->
                @if($review->pros || $review->cons)
                    <div class="grid grid-cols-2 gap-4 mb-8 pb-8 border-b border-white/10">
                        @if($review->pros)
                            <div>
                                <p class="font-semibold text-green-400 mb-2">
                                    <i class="fas fa-check-circle mr-1"></i> Ce qui a plu
                                </p>
                                <p class="text-sm text-slate-300">{{ $review->pros }}</p>
                            </div>
                        @endif
                        @if($review->cons)
                            <div>
                                <p class="font-semibold text-red-400 mb-2">
                                    <i class="fas fa-times-circle mr-1"></i> Points à améliorer
                                </p>
                                <p class="text-sm text-slate-300">{{ $review->cons }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Recommandation -->
                @if($review->would_recommend)
                    <div class="p-3 rounded-lg bg-red-500/10 border border-red-500/20 mb-8">
                        <p class="text-sm text-red-400">
                            <i class="fas fa-heart mr-2"></i>
                            <strong>Recommandé</strong> - L'auteur recommande cette box
                        </p>
                    </div>
                @endif

                <!-- Détails du produit reviewed -->
                <div class="border-t border-white/10 pt-8 mb-8">
                    <h3 class="font-bold text-lg mb-4">Box Évaluée</h3>
                    <a href="{{ route('catalog.show', $review->box) }}" class="group block">
                        <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-white/5 transition">
                            <div class="text-4xl">{{ $review->box->emoji ?? '📦' }}</div>
                            <div class="flex-1">
                                <p class="font-semibold group-hover:text-indigo-400 transition">{{ $review->box->title }}</p>
                                <p class="text-sm text-slate-400">{{ $review->box->category->name }}</p>
                                <p class="text-xs text-slate-500 mt-2">{{ Str::limit($review->box->description, 100) }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Utilité de l'avis -->
                <div class="border-t border-white/10 pt-8">
                    <h3 class="font-bold text-lg mb-4">Cet avis a-t-il été utile?</h3>
                    <div class="flex items-center gap-4">
                        <button onclick="markHelpful()" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-400 transition">
                            <i class="fas fa-thumbs-up"></i>
                            <span id="helpful-count">{{ $review->helpful_count ?? 0 }}</span>
                            <span class="text-sm">Utile</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre latérale -->
        <div class="space-y-6">
            @if(auth()->id() === $review->user_id || auth()->user()->is_admin)
                <div class="glass p-6">
                    <h3 class="font-bold mb-4">Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('reviews.edit', $review) }}" class="block text-center py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold transition">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                        <button onclick="deleteReview()" class="block w-full py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded-lg text-sm transition">
                            <i class="fas fa-trash mr-2"></i> Supprimer
                        </button>
                    </div>
                </div>
            @endif

            <div class="glass p-6">
                <h3 class="font-bold mb-4">Informations</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-slate-400 mb-2">Statut</p>
                        <span class="status-badge {{ $review->is_verified ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-slate-500/10 text-slate-400 border border-slate-500/20' }}">
                            {{ $review->is_verified ? 'Achat Vérifié' : 'Non Vérifié' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-2">Note</p>
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-600' }}"></i>
                                @endfor
                            </div>
                            <span class="font-bold">{{ $review->rating }}/5</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-2">Utilité</p>
                        <p class="font-bold">{{ $review->helpful_count ?? 0 }} personne(s)</p>
                    </div>
                </div>
            </div>

            <div class="glass p-6">
                <h3 class="font-bold mb-4">Auteur</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-slate-400 mb-1">Nom</p>
                        <p class="font-semibold">{{ $review->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-1">Email</p>
                        <p class="text-indigo-400">{{ $review->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-1">Avis publiés</p>
                        <p class="font-semibold">{{ $review->user->reviews()->count() }}</p>
                    </div>
                </div>
            </div>

            @if(auth()->user()?->is_admin)
                <div class="glass p-6 border border-red-500/20">
                    <h3 class="font-bold mb-4 text-red-400">Modération</h3>
                    <p class="text-xs text-slate-400 mb-3">Admin uniquement</p>
                    <button onclick="reportReview()" class="block w-full py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded-lg text-sm transition">
                        <i class="fas fa-flag mr-2"></i> Signaler
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function markHelpful() {
    fetch('{{ route("reviews.helpful", $review) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('helpful-count').textContent = data.helpful_count;
    })
    .catch(error => console.error('Error:', error));
}

function deleteReview() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet avis?')) {
        fetch('{{ route("reviews.destroy", $review) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '{{ route("reviews.index") }}';
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function reportReview() {
    // Pour admin seulement
    alert('Cette fonctionnalité est disponible pour les administrateurs');
}
</script>
@endsection
