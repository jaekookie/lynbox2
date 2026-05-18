@extends('layouts.app')

@section('title', 'Mes Avis - LynBox')

@section('content')
<div class="space-y-8">
    <header class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold">Mes Avis</h1>
            <p class="text-slate-400">Vos notes et commentaires sur les boxes reçues</p>
        </div>
        <button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-semibold shadow-lg shadow-indigo-600/30 transition">
            + Nouvel avis
        </button>
    </header>

    <section>
        @if($reviews->isEmpty())
            <div class="glass p-8 text-center">
                <i class="fas fa-star text-5xl text-slate-400 mb-4"></i>
                <p class="text-slate-400 mb-4">Vous n'avez pas encore d'avis.</p>
                <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-semibold transition">
                    Laisser un avis
                </button>
            </div>
        @else
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="glass p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold">{{ $review->box->title }}</h3>
                                <p class="text-xs text-slate-500">{{ $review->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex space-x-1">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star {{ $i < $review->rating ? 'text-yellow-400' : 'text-slate-600' }}"></i>
                                @endfor
                            </div>
                        </div>

                        <p class="text-slate-300 mb-4">{{ $review->comment }}</p>

                        <div class="flex items-center justify-between pt-4 border-t border-white/10">
                            <div class="text-xs text-slate-500">
                                <i class="fas fa-thumbs-up mr-1"></i>
                                {{ $review->helpful_count }} personne(s) trouvent cet avis utile
                            </div>
                            <div class="space-x-2">
                                <button class="text-indigo-400 hover:text-indigo-300 text-sm">Éditer</button>
                                <button class="text-red-400 hover:text-red-300 text-sm">Supprimer</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
