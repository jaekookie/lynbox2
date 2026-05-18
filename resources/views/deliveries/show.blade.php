@extends('layouts.app')

@section('title', 'Suivi de Livraison - LynBox')

@section('content')
<div class="space-y-8">
    <a href="{{ route('deliveries.index') }}" class="flex items-center text-indigo-400 hover:text-indigo-300 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux livraisons
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="glass p-8">
                <h1 class="text-3xl font-bold mb-2">{{ $delivery->subscription->box->title }}</h1>
                <p class="text-slate-400 mb-6">Suivi de la livraison - {{ $delivery->tracking_number }}</p>

                <div class="relative pt-12 pb-8">
                    <div class="absolute top-0 left-0 w-full h-1 bg-white/10 rounded-full"></div>
                    <div class="absolute top-0 left-0 w-{{ $delivery->progressPercentage() > 0 ? intval($delivery->progressPercentage() / 25) . '/4' : '0' }} h-1 bg-indigo-500 rounded-full shadow-[0_0_15px_#6366f1]"></div>

                    <div class="relative flex justify-between">
                        <div class="flex flex-col items-center">
                            <div class="h-8 w-8 {{ $delivery->progressPercentage() >= 25 ? 'bg-indigo-500 ring-4 ring-indigo-500/20' : 'bg-slate-700' }} rounded-full flex items-center justify-center text-xs {{ $delivery->progressPercentage() >= 25 ? 'text-white' : '' }}">
                                {{ $delivery->progressPercentage() >= 25 ? '✓' : '' }}
                            </div>
                            <span class="text-xs mt-3 font-bold {{ $delivery->progressPercentage() >= 25 ? 'text-indigo-400' : 'text-slate-500' }} uppercase">Commandé</span>
                            <span class="text-xs text-slate-600 mt-1">{{ $delivery->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="h-8 w-8 {{ $delivery->progressPercentage() >= 50 ? 'bg-indigo-500 ring-4 ring-indigo-500/20' : 'bg-slate-700' }} rounded-full flex items-center justify-center text-xs {{ $delivery->progressPercentage() >= 50 ? 'text-white' : '' }}">
                                {{ $delivery->progressPercentage() >= 50 ? '✓' : '' }}
                            </div>
                            <span class="text-xs mt-3 font-bold {{ $delivery->progressPercentage() >= 50 ? 'text-indigo-400' : 'text-slate-500' }} uppercase">Préparé</span>
                            <span class="text-xs text-slate-600 mt-1">En cours</span>
                        </div>

                        <div class="flex flex-col items-center">
                            <div class="h-10 w-10 {{ $delivery->progressPercentage() >= 66 ? 'bg-indigo-500 ring-4 ring-indigo-500/20 animate-pulse' : 'bg-slate-700' }} rounded-full flex items-center justify-center text-sm {{ $delivery->progressPercentage() >= 66 ? 'text-white' : '' }}">
                                <i class="fas fa-truck"></i>
                            </div>
                            <span class="text-xs mt-3 font-bold {{ $delivery->progressPercentage() >= 66 ? 'text-white' : 'text-slate-500' }} uppercase">Expédié</span>
                            <span class="text-xs text-slate-600 mt-1">{{ optional($delivery->shipped_at)->format('d M Y') ?? 'Bientôt' }}</span>
                        </div>

                        <div class="flex flex-col items-center {{ $delivery->progressPercentage() >= 100 ? '' : 'opacity-30' }}">
                            <div class="h-8 w-8 {{ $delivery->progressPercentage() >= 100 ? 'bg-indigo-500 ring-4 ring-indigo-500/20' : 'bg-slate-700' }} rounded-full flex items-center justify-center text-xs {{ $delivery->progressPercentage() >= 100 ? 'text-white' : '' }}">
                                {{ $delivery->progressPercentage() >= 100 ? '✓' : '' }}
                            </div>
                            <span class="text-xs mt-3 font-bold {{ $delivery->progressPercentage() >= 100 ? 'text-indigo-400' : 'text-slate-500' }} uppercase">Livré</span>
                            <span class="text-xs text-slate-600 mt-1">{{ optional($delivery->delivered_at)->format('d M Y') ?? 'À venir' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass p-8">
                <h2 class="text-xl font-bold mb-6">Détails de la livraison</h2>

                <div class="space-y-4">
                    <div class="flex justify-between pb-4 border-b border-white/10">
                        <span class="text-slate-400">Numéro de suivi</span>
                        <span class="font-mono font-semibold">{{ $delivery->tracking_number }}</span>
                    </div>
                    <div class="flex justify-between pb-4 border-b border-white/10">
                        <span class="text-slate-400">Statut</span>
                        <span class="font-semibold">{{ $delivery->getStatusLabel() }}</span>
                    </div>
                    <div class="flex justify-between pb-4 border-b border-white/10">
                        <span class="text-slate-400">Adresse de livraison</span>
                        <span class="text-right font-semibold max-w-xs">{{ $delivery->delivery_address }}</span>
                    </div>
                    <div class="flex justify-between pb-4 border-b border-white/10">
                        <span class="text-slate-400">Livraison estimée</span>
                        <span class="font-semibold text-indigo-400">{{ $delivery->estimated_delivery?->format('l, j M Y') ?? 'À déterminer' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Abonnement</span>
                        <a href="{{ route('subscriptions.show', $delivery->subscription) }}" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                            {{ $delivery->subscription->box->title }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass p-6">
                <h3 class="font-bold mb-4">Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="h-3 w-3 {{ $delivery->isPreparing() ? 'bg-blue-500 animate-pulse' : 'bg-slate-700' }} rounded-full"></div>
                        <span class="text-sm">En préparation</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="h-3 w-3 {{ $delivery->isShipped() ? 'bg-yellow-500 animate-pulse' : 'bg-slate-700' }} rounded-full"></div>
                        <span class="text-sm">Expédié</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="h-3 w-3 {{ $delivery->isDelivered() ? 'bg-green-500' : 'bg-slate-700' }} rounded-full"></div>
                        <span class="text-sm">Livré</span>
                    </div>
                </div>
            </div>

            <div class="glass p-6">
                <h3 class="font-bold mb-4">Actions</h3>
                <div class="space-y-2">
                    @if($delivery->isDelivered())
                        <a href="{{ route('reviews.store') }}" class="block text-center py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold transition">
                            Laisser un avis
                        </a>
                    @endif
                    <button onclick="copyTracking()" class="block w-full py-2 bg-white/5 hover:bg-white/10 rounded-lg text-sm transition">
                        Copier le suivi
                    </button>
                </div>
            </div>

            <div class="glass p-6">
                <h3 class="font-bold mb-4">Support</h3>
                <p class="text-xs text-slate-400 mb-4">Besoin d'aide? Contactez notre équipe de support.</p>
                <a href="#" class="text-indigo-400 hover:text-indigo-300 text-sm">
                    Ouvrir un ticket <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyTracking() {
    const tracking = '{{ $delivery->tracking_number }}';
    navigator.clipboard.writeText(tracking).then(() => {
        alert('Numéro de suivi copié!');
    });
}
</script>
@endsection
