@extends('layouts.public')

@section('title', 'Bienvenue - LynBox')

@section('content')
<div class="space-y-12">
    <!-- Hero Section -->
    <div class="text-center py-12">
        <h1 class="text-5xl md:text-6xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">
            🎁 LynBox
        </h1>
        <p class="text-xl text-slate-300 mb-8">Découvrez le plaisir des boxes mensuelles premium</p>
        
        @auth
            <a href="{{ route('catalog.index') }}" class="inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                Parcourir les Boxes
            </a>
        @else
            <div class="flex gap-4 justify-center">
                <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                    Se connecter
                </a>
                <a href="{{ route('register') }}" class="px-8 py-3 border-2 border-indigo-400 text-indigo-400 hover:bg-indigo-400/10 rounded-lg font-semibold transition">
                    S'inscrire
                </a>
            </div>
        @endauth
    </div>

    <!-- Features -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-8 text-center">
            <div class="text-4xl mb-4">📦</div>
            <h3 class="font-bold mb-2">Boxes Curatées</h3>
            <p class="text-slate-400">Chaque box est sélectionnée avec soin par nos experts</p>
        </div>
        <div class="glass p-8 text-center">
            <div class="text-4xl mb-4">💳</div>
            <h3 class="font-bold mb-2">Paiement Flexible</h3>
            <p class="text-slate-400">Pause, modifiez ou annulez votre abonnement quand vous voulez</p>
        </div>
        <div class="glass p-8 text-center">
            <div class="text-4xl mb-4">🎯</div>
            <h3 class="font-bold mb-2">Points de Fidélité</h3>
            <p class="text-slate-400">Gagnez des points et obtenez des réductions exclusives</p>
        </div>
    </div>

    <!-- Categories Preview -->
    @auth
        <div>
            <h2 class="text-3xl font-bold mb-8">Nos Catégories</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('catalog.index', ['category' => 'beaute']) }}" class="glass p-6 hover:border-indigo-400 transition group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition">💄</div>
                    <h3 class="font-bold mb-2">Beauté</h3>
                    <p class="text-slate-400 text-sm">Produits de soins premium</p>
                </a>
                <a href="{{ route('catalog.index', ['category' => 'alimentation']) }}" class="glass p-6 hover:border-indigo-400 transition group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition">🍕</div>
                    <h3 class="font-bold mb-2">Alimentation</h3>
                    <p class="text-slate-400 text-sm">Sélection culinaire premium</p>
                </a>
                <a href="{{ route('catalog.index', ['category' => 'livres']) }}" class="glass p-6 hover:border-indigo-400 transition group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition">📚</div>
                    <h3 class="font-bold mb-2">Livres</h3>
                    <p class="text-slate-400 text-sm">Sélection de lecture curatée</p>
                </a>
            </div>
        </div>
    @endauth

    <!-- Testimonials -->
    <div>
        <h2 class="text-3xl font-bold mb-8 text-center">Ce que nos clients disent</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-yellow-400"></i>
                    @endfor
                </div>
                <p class="text-slate-300 mb-4">"Une expérience incroyable! J'adore découvrir chaque mois une nouvelle sélection de produits premium."</p>
                <p class="font-semibold">Marie D.</p>
            </div>
            <div class="glass p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-yellow-400"></i>
                    @endfor
                </div>
                <p class="text-slate-300 mb-4">"Le service client est excellent et les boîtes arrivent toujours parfaitement emballées."</p>
                <p class="font-semibold">Jean P.</p>
            </div>
            <div class="glass p-6">
                <div class="flex items-center gap-1 mb-3">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-yellow-400"></i>
                    @endfor
                </div>
                <p class="text-slate-300 mb-4">"Les points de fidélité permettent de faire de vraies économies. Je recommande vivement!"</p>
                <p class="font-semibold">Sophie L.</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    @guest
        <div class="glass p-12 text-center border border-indigo-400/20 bg-indigo-500/5">
            <h2 class="text-3xl font-bold mb-4">Prêt à découvrir votre prochaine box?</h2>
            <p class="text-slate-300 mb-8">Rejoignez des milliers de clients satisfaits</p>
            <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                Commencer Maintenant
            </a>
        </div>
    @endguest
</div>
@endsection
