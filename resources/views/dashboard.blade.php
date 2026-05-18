@extends('layouts.app')

@section('title', 'Dashboard - LynBox')

@section('content')
<div class="space-y-8">
    <header class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Ravi de vous revoir, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="text-slate-400">
                @if($latestDelivery)
                    Votre prochaine livraison arrivera le {{ $latestDelivery->estimated_delivery?->format('d M') ?? 'bientôt' }}.
                @else
                    Consultez notre catalogue pour découvrir nos boxes.
                @endif
            </p>
        </div>
        <div class="flex space-x-4">
            <button class="h-12 w-12 glass flex items-center justify-center hover:bg-white/10 transition">
                <i class="far fa-bell text-slate-300"></i>
            </button>
            <a href="{{ route('catalog.index') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-semibold shadow-lg shadow-indigo-600/30 transition">
                Explorer les Box
            </a>
        </div>
    </header>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 h-24 w-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition"></div>
            <p class="text-slate-400 text-sm font-medium">Abonnements Actifs</p>
            <h2 class="text-4xl font-bold mt-2">{{ $activeSubscriptions }}</h2>
            <p class="text-green-400 text-xs mt-2"><i class="fas fa-arrow-up mr-1"></i> {{ $activeSubscriptions > 0 ? '+1 ce mois-ci' : 'Aucun abonnement' }}</p>
        </div>
        <div class="glass p-6">
            <p class="text-slate-400 text-sm font-medium">Box Reçues</p>
            <h2 class="text-4xl font-bold mt-2">{{ $totalBoxesReceived }}</h2>
            <p class="text-slate-500 text-xs mt-2">Toutes catégories confondues</p>
        </div>
        <div class="glass p-6 border-indigo-500/30">
            <p class="text-indigo-400 text-sm font-medium">Points Fidélité</p>
            <h2 class="text-4xl font-bold mt-2">{{ auth()->user()->loyaltyPoints->balance ?? 0 }}</h2>
            <p class="text-indigo-300/50 text-xs mt-2">Bon de {{ intval((auth()->user()->loyaltyPoints->balance ?? 0) / 10) }}€ disponible</p>
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="text-xl font-bold flex items-center">
            <i class="fas fa-rocket mr-3 text-indigo-400"></i> Vos Box en cours
        </h2>

        @if($user->subscriptions->isEmpty())
            <div class="glass p-8 text-center">
                <i class="fas fa-inbox text-5xl text-slate-400 mb-4"></i>
                <p class="text-slate-400 mb-4">Vous n'avez pas d'abonnements actifs.</p>
                <a href="{{ route('catalog.index') }}" class="inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-semibold transition">
                    Découvrir les boxes
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4">
                @foreach($user->subscriptions as $subscription)
                    @if($subscription->status === 'active' || $subscription->status === 'paused')
                        <div class="glass p-6 flex flex-col md:flex-row items-center justify-between box-card">
                            <div class="flex items-center space-x-6">
                                <div class="h-20 w-20 bg-gradient-to-br from-amber-400 to-orange-600 rounded-2xl flex items-center justify-center text-3xl">
                                    {{ $subscription->box->emoji ?? '📦' }}
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <h3 class="font-bold text-lg">{{ $subscription->box->title }}</h3>
                                        <span class="ml-3 status-badge {{ $subscription->status === 'active' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                            {{ $subscription->status === 'active' ? 'Actif' : 'En pause' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-400">
                                        {{ ucfirst($subscription->box->billing_cycle) }} • Renouvellement le {{ $subscription->next_renewal_date->format('d M') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 flex space-x-3">
                                @if($subscription->status === 'active')
                                    <button onclick="pauseSubscription({{ $subscription->id }})" class="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-sm transition">
                                        Mettre en pause
                                    </button>
                                    <a href="{{ route('subscriptions.show', $subscription) }}" class="px-4 py-2 bg-indigo-500/20 text-indigo-400 hover:bg-indigo-500/30 rounded-lg text-sm font-semibold transition">
                                        Détails livraison
                                    </a>
                                @else
                                    <button onclick="reactivateSubscription({{ $subscription->id }})" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 rounded-lg text-sm text-white transition font-bold">
                                        Réactiver
                                    </button>
                                    <a href="{{ route('subscriptions.show', $subscription) }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-sm transition border border-white/5">
                                        Gérer
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </section>

    @if($latestDelivery)
        <section class="glass p-8 bg-gradient-to-r from-indigo-900/20 to-transparent border-indigo-500/20">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-xl font-bold">Suivi en temps réel</h2>
                    <p class="text-sm text-slate-400 italic">Box {{ $latestDelivery->subscription->box->title }} 📦</p>
                </div>
                <div class="text-right">
                    <span class="text-xs uppercase tracking-widest text-indigo-400 font-bold">Arrivée estimée</span>
                    <p class="text-lg font-bold">{{ $latestDelivery->estimated_delivery?->format('l, j M') ?? 'Bientôt' }}</p>
                </div>
            </div>

            <div class="relative pt-8 pb-4">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-white/10 -translate-y-1/2 rounded-full"></div>
                <div class="absolute top-1/2 left-0 w-{{ $latestDelivery->progressPercentage() > 0 ? intval($latestDelivery->progressPercentage() / 25) . '/4' : '0' }} h-1 bg-indigo-500 -translate-y-1/2 rounded-full shadow-[0_0_15px_#6366f1]"></div>

                <div class="relative flex justify-between">
                    <div class="flex flex-col items-center">
                        <div class="h-6 w-6 {{ $latestDelivery->progressPercentage() >= 25 ? 'bg-indigo-500' : 'bg-slate-700' }} rounded-full flex items-center justify-center text-[10px] {{ $latestDelivery->progressPercentage() >= 25 ? 'text-white' : '' }}">
                            {{ $latestDelivery->progressPercentage() >= 25 ? '✓' : '' }}
                        </div>
                        <span class="text-[10px] mt-2 font-bold {{ $latestDelivery->progressPercentage() >= 25 ? 'text-indigo-400' : 'text-slate-500' }} uppercase">Commandé</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="h-6 w-6 {{ $latestDelivery->progressPercentage() >= 50 ? 'bg-indigo-500' : 'bg-slate-700' }} rounded-full flex items-center justify-center text-[10px] {{ $latestDelivery->progressPercentage() >= 50 ? 'text-white' : '' }}">
                            {{ $latestDelivery->progressPercentage() >= 50 ? '✓' : '' }}
                        </div>
                        <span class="text-[10px] mt-2 font-bold {{ $latestDelivery->progressPercentage() >= 50 ? 'text-indigo-400' : 'text-slate-500' }} uppercase">Préparé</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="h-8 w-8 {{ $latestDelivery->progressPercentage() >= 66 ? 'bg-indigo-500 ring-4 ring-indigo-500/20 animate-pulse' : 'bg-slate-700' }} rounded-full flex items-center justify-center text-xs {{ $latestDelivery->progressPercentage() >= 66 ? 'text-white' : '' }}">
                            <i class="fas fa-truck"></i>
                        </div>
                        <span class="text-[10px] mt-2 font-bold {{ $latestDelivery->progressPercentage() >= 66 ? 'text-white' : 'text-slate-500' }} uppercase">Expédié</span>
                    </div>
                    <div class="flex flex-col items-center {{ $latestDelivery->progressPercentage() >= 100 ? '' : 'opacity-30' }}">
                        <div class="h-6 w-6 {{ $latestDelivery->progressPercentage() >= 100 ? 'bg-indigo-500' : 'bg-slate-700' }} rounded-full flex items-center justify-center text-[10px] {{ $latestDelivery->progressPercentage() >= 100 ? 'text-white' : '' }}">
                            {{ $latestDelivery->progressPercentage() >= 100 ? '✓' : '' }}
                        </div>
                        <span class="text-[10px] mt-2 font-bold {{ $latestDelivery->progressPercentage() >= 100 ? 'text-indigo-400' : 'text-slate-500' }} uppercase">Livré</span>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>

<script>
function pauseSubscription(subscriptionId) {
    if (confirm('Êtes-vous sûr de vouloir mettre en pause cet abonnement?')) {
        // Implementation will be added when forms are ready
    }
}

function reactivateSubscription(subscriptionId) {
    if (confirm('Êtes-vous sûr de vouloir réactiver cet abonnement?')) {
        // Implementation will be added when forms are ready
    }
}
</script>
@endsection
