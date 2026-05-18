@extends('layouts.app')

@section('title', 'Catalogue - LynBox')

@section('content')
<div class="space-y-8">
    <header>
        <div class="mb-6">
            <h1 class="text-3xl font-bold">Catalogue des Box</h1>
            <p class="text-slate-400">Découvrez toutes nos offres mensuelles</p>
        </div>

        <div class="glass p-4 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Rechercher une box..." class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500/50">
            </div>
            <select id="categoryFilter" class="bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-indigo-500/50">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </header>

    <section>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($boxes as $box)
                <a href="{{ route('catalog.show', $box) }}" class="glass p-6 rounded-2xl hover:border-indigo-500/40 transition group cursor-pointer overflow-hidden">
                    <div class="relative h-40 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-xl flex items-center justify-center text-5xl mb-4 group-hover:scale-105 transition overflow-hidden">
                        @if($box->image_url)
                            <img src="{{ $box->image_url }}" alt="{{ $box->title }}" class="w-full h-full object-cover">
                        @else
                            {{ $box->emoji ?? '📦' }}
                        @endif
                    </div>

                    <div class="mb-4">
                        <h3 class="font-bold text-lg mb-1">{{ $box->title }}</h3>
                        <p class="text-xs text-indigo-400 font-semibold uppercase">{{ $box->category->name }}</p>
                    </div>

                    <p class="text-sm text-slate-400 mb-4 line-clamp-2">{{ $box->description }}</p>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-1">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star {{ $i < intval($box->averageRating()) ? 'text-yellow-400' : 'text-slate-600' }} text-xs"></i>
                            @endfor
                            <span class="text-xs text-slate-500 ml-2">({{ $box->reviewCount() }})</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-indigo-400">{{ number_format($box->price, 2, ',', ' ') }}€</span>
                        <span class="text-xs {{ $box->isInStock() ? 'text-green-400' : 'text-red-400' }}">
                            {{ $box->isInStock() ? 'En stock' : 'Rupture' }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full glass p-8 text-center">
                    <i class="fas fa-inbox text-5xl text-slate-400 mb-4"></i>
                    <p class="text-slate-400">Aucune box trouvée.</p>
                </div>
            @endforelse
        </div>

        @if($boxes->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $boxes->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
