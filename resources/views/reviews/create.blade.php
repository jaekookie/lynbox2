@extends('layouts.app')

@section('title', $review ? 'Modifier Avis' : 'Ajouter un Avis - LynBox')

@section('content')
<div class="space-y-8 max-w-2xl">
    <a href="{{ route('reviews.index') }}" class="flex items-center text-indigo-400 hover:text-indigo-300 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux avis
    </a>

    <div>
        <h1 class="text-3xl font-bold mb-2">
            {{ $review ? 'Modifier votre avis' : 'Retourner un avis' }}
        </h1>
        <p class="text-slate-400">Partagez votre expérience à propos de cette box</p>
    </div>

    <form action="{{ $review ? route('reviews.update', $review) : route('reviews.store') }}" method="POST" class="space-y-6">
        @csrf
        @if($review)
            @method('PATCH')
        @endif

        <!-- Sélection de la box (si c'est une création) -->
        @if(!$review)
            <div class="glass p-6">
                <label class="block text-sm font-semibold mb-3">
                    <i class="fas fa-box mr-2 text-indigo-400"></i>
                    Quelle box souhaitez-vous évaluer?
                </label>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @forelse(auth()->user()->subscriptions as $subscription)
                        <label class="flex items-start gap-3 p-3 rounded-lg hover:bg-white/5 cursor-pointer transition">
                            <input type="radio" name="box_id" value="{{ $subscription->box_id }}" class="mt-1" required>
                            <div class="flex-1">
                                <p class="font-semibold">{{ $subscription->box->title }}</p>
                                <p class="text-xs text-slate-400">{{ $subscription->box->category->name }}</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Abonné depuis {{ $subscription->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </label>
                    @empty
                        <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-lg text-amber-400 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            Vous n'avez pas d'abonnement actif pour laisser un avis
                        </div>
                    @endforelse
                </div>
                @error('box_id')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        @else
            <div class="glass p-6">
                <p class="text-sm font-semibold mb-3">
                    <i class="fas fa-box mr-2 text-indigo-400"></i>
                    Box évaluée
                </p>
                <div class="flex items-center gap-3 p-3 rounded-lg bg-indigo-500/5 border border-indigo-500/20">
                    <span class="text-2xl">{{ $review->box->emoji ?? '📦' }}</span>
                    <div>
                        <p class="font-semibold">{{ $review->box->title }}</p>
                        <p class="text-xs text-slate-400">{{ $review->box->category->name }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Évaluation par étoiles -->
        <div class="glass p-6">
            <label class="block text-sm font-semibold mb-4">
                <i class="fas fa-star mr-2 text-yellow-400"></i>
                Comment évalueriez-vous cette box?
            </label>
            <div class="flex gap-2 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $i }}" class="hidden" required
                            {{ $review && $review->rating == $i ? 'checked' : '' }}>
                        <i class="fas fa-star text-3xl transition hover:scale-110"
                            id="star-{{ $i }}"
                            style="color: {{ $review && $review->rating >= $i ? '#fbbf24' : '#475569' }}"></i>
                    </label>
                @endfor
            </div>
            <p class="text-xs text-slate-400 mb-3">
                <span id="rating-text">{{ $review ? ucfirst(config('messages.ratings.' . $review->rating, 'Cliquez pour évaluer')) : 'Cliquez sur une étoile' }}</span>
            </p>
            @error('rating')
                <p class="text-red-400 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Commentaire -->
        <div class="glass p-6">
            <label class="block text-sm font-semibold mb-3">
                <i class="fas fa-comment mr-2 text-indigo-400"></i>
                Votre avis (obligatoire)
            </label>
            <textarea name="comment" rows="6" placeholder="Partagez votre expérience détaillée..."
                class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition resize-none"
                required>{{ $review?->comment }}</textarea>
            <p class="text-xs text-slate-400 mt-2">
                <span id="char-count">{{ $review ? strlen($review->comment) : 0 }}</span>/700 caractères
            </p>
            @error('comment')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Points positifs/négatifs -->
        <div class="glass p-6">
            <label class="block text-sm font-semibold mb-4">
                <i class="fas fa-check-circle mr-2 text-green-400"></i>
                Ce qui vous a plu (optionnel)
            </label>
            <textarea name="pros" rows="3" placeholder="Par exemple: Emballage de qualité, prix attraktif..."
                class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition resize-none">{{ $review?->pros }}</textarea>
        </div>

        <div class="glass p-6">
            <label class="block text-sm font-semibold mb-4">
                <i class="fas fa-times-circle mr-2 text-red-400"></i>
                Points à améliorer (optionnel)
            </label>
            <textarea name="cons" rows="3" placeholder="Par exemple: Délai de livraison, variété limitée..."
                class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition resize-none">{{ $review?->cons }}</textarea>
        </div>

        <!-- Recommandation -->
        <div class="glass p-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="would_recommend" class="w-4 h-4 rounded accent-indigo-500" 
                    {{ $review && $review->would_recommend ? 'checked' : '' }}>
                <span class="font-semibold">
                    <i class="fas fa-heart text-red-400 mr-2"></i>
                    Je recommande cette box
                </span>
            </label>
        </div>

        <!-- Photos (future feature) -->
        <div class="glass p-6 opacity-50 cursor-not-allowed">
            <label class="block text-sm font-semibold mb-3 text-slate-400">
                <i class="fas fa-image mr-2"></i>
                Photos (bientôt disponible)
            </label>
            <p class="text-xs text-slate-500">La possibilité d'ajouter des photos arrivera prochainement</p>
        </div>

        <!-- Boutons -->
        <div class="flex gap-4">
            <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                <i class="fas fa-check mr-2"></i>
                {{ $review ? 'Mettre à jour l\'avis' : 'Publier l\'avis' }}
            </button>
            <a href="{{ route('reviews.index') }}" class="flex-1 py-3 bg-white/5 hover:bg-white/10 rounded-lg font-semibold transition text-center">
                <i class="fas fa-times mr-2"></i> Annuler
            </a>
        </div>

        <!-- Avertissement de suppression -->
        @if($review && auth()->id() === $review->user_id)
            <div class="pt-4 border-t border-white/10">
                <button type="button" onclick="deleteReview()" class="text-red-400 hover:text-red-300 text-sm flex items-center gap-2">
                    <i class="fas fa-trash"></i> Supprimer cet avis
                </button>
            </div>
        @endif
    </form>
</div>

<script>
// Étoiles interactives
document.querySelectorAll('input[name="rating"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const rating = this.value;
        for (let i = 1; i <= 5; i++) {
            const star = document.getElementById(`star-${i}`);
            if (i <= rating) {
                star.style.color = '#fbbf24';
                star.classList.add('animate-pulse');
            } else {
                star.style.color = '#475569';
                star.classList.remove('animate-pulse');
            }
        }
        
        const ratings = {
            1: 'Mauvais',
            2: 'Acceptable',
            3: 'Bon',
            4: 'Très bon',
            5: 'Excellent'
        };
        document.getElementById('rating-text').textContent = ratings[rating] || 'Cliquez pour évaluer';
    });
});

// Compteur de caractères
document.querySelector('textarea[name="comment"]').addEventListener('input', function() {
    document.getElementById('char-count').textContent = this.value.length;
});

// Suppression
function deleteReview() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet avis? Cette action est irréversible.')) {
        fetch('{{ route("reviews.destroy", $review ?? 0) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (response.ok) window.location.href = '{{ route("reviews.index") }}';
        });
    }
}
</script>
@endsection
