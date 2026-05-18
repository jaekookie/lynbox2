@extends('layouts.app')

@section('title', $review ? 'Modifier l\'avis' : 'Créer un avis - LynBox')

@section('content')
<div class="space-y-8 max-w-2xl">
    <a href="{{ route('reviews.index') }}" class="flex items-center text-indigo-400 hover:text-indigo-300 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux avis
    </a>

    <div>
        <h1 class="text-3xl font-bold mb-2">
            {{ $review ? '✏️ Modifier votre avis' : '⭐ Ajouter un avis' }}
        </h1>
        <p class="text-slate-400">{{ $review ? 'Mettez à jour votre avis sur cette box' : 'Partagez votre expérience avec notre communauté' }}</p>
    </div>

    <form action="{{ $review ? route('reviews.update', $review) : route('reviews.store') }}" method="POST" class="space-y-6">
        @csrf
        @if($review)
            @method('PATCH')
            <input type="hidden" name="box_id" value="{{ $review->box_id }}">
        @endif

        <!-- Sélection de la box -->
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

        <!-- Évaluation -->
        <div class="glass p-6">
            <label class="block text-sm font-semibold mb-4">
                <i class="fas fa-star mr-2 text-yellow-400"></i>
                Comment évalueriez-vous cette box?
            </label>
            <div class="flex gap-2 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $i }}" class="hidden" required
                            {{ ($review && $review->rating == $i) || old('rating') == $i ? 'checked' : '' }}
                            onchange="updateRating(this.value)">
                        <i class="fas fa-star text-3xl transition hover:scale-110" id="star-{{ $i }}"
                            style="color: {{ ($review && $review->rating >= $i) || old('rating') >= $i ? '#fbbf24' : '#475569' }}"></i>
                    </label>
                @endfor
            </div>
            <p class="text-xs text-slate-400">
                <span id="rating-text">
                    @if($review)
                        {{ config('messages.ratings.' . $review->rating, 'Cliquez pour évaluer') }}
                    @elseif(old('rating'))
                        {{ config('messages.ratings.' . old('rating'), 'Cliquez pour évaluer') }}
                    @else
                        Cliquez sur une étoile
                    @endif
                </span>
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
                required>{{ $review?->comment ?? old('comment') }}</textarea>
            <p class="text-xs text-slate-400 mt-2">
                <span id="char-count">{{ $review ? strlen($review->comment) : (old('comment') ? strlen(old('comment')) : 0) }}</span>/700 caractères
            </p>
            @error('comment')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Points positifs -->
        <div class="glass p-6">
            <label class="block text-sm font-semibold mb-4">
                <i class="fas fa-check-circle mr-2 text-green-400"></i>
                Ce qui vous a plu (optionnel)
            </label>
            <textarea name="pros" rows="3" placeholder="Par exemple: Emballage de qualité, prix attraktif..."
                class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition resize-none">{{ $review?->pros ?? old('pros') }}</textarea>
        </div>

        <!-- Points à améliorer -->
        <div class="glass p-6">
            <label class="block text-sm font-semibold mb-4">
                <i class="fas fa-times-circle mr-2 text-red-400"></i>
                Points à améliorer (optionnel)
            </label>
            <textarea name="cons" rows="3" placeholder="Par exemple: Délai de livraison, variété limitée..."
                class="w-full bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 p-3 focus:border-indigo-500 focus:outline-none transition resize-none">{{ $review?->cons ?? old('cons') }}</textarea>
        </div>

        <!-- Recommandation -->
        <div class="glass p-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="would_recommend" class="w-4 h-4 rounded accent-indigo-500"
                    {{ ($review && $review->would_recommend) || old('would_recommend') ? 'checked' : '' }}>
                <span class="font-semibold">
                    <i class="fas fa-heart text-red-400 mr-2"></i>
                    Je recommande cette box
                </span>
            </label>
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
function updateRating(rating) {
    const ratings = {
        1: 'Mauvais',
        2: 'Acceptable',
        3: 'Bon',
        4: 'Très bon',
        5: 'Excellent'
    };
    
    for (let i = 1; i <= 5; i++) {
        const star = document.getElementById(`star-${i}`);
        star.style.color = i <= rating ? '#fbbf24' : '#475569';
    }
    
    document.getElementById('rating-text').textContent = ratings[rating] || 'Cliquez pour évaluer';
}

document.querySelector('textarea[name="comment"]')?.addEventListener('input', function() {
    document.getElementById('char-count').textContent = this.value.length;
});

function deleteReview() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet avis?')) {
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
