@extends('layouts.app')

@section('title', 'Points de Fidélité - LynBox')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold mb-2">🎯 Programme de Fidélité</h1>
        <p class="text-slate-400">Gagnez et utilisez vos points à chaque achat</p>
    </div>

    <!-- Résumé Points -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-6 border border-indigo-400/20">
            <p class="text-slate-400 text-sm mb-2">Points Disponibles</p>
            <p class="text-4xl font-bold text-indigo-400 mb-2">{{ $loyaltyPoints->balance ?? 0 }}</p>
            <p class="text-xs text-slate-500">{{ number_format(($loyaltyPoints->balance ?? 0) / 10, 2, ',', ' ') }}€ de crédit</p>
        </div>

        <div class="glass p-6 border border-yellow-400/20">
            <p class="text-slate-400 text-sm mb-2">Niveau Actuel</p>
            <p class="text-2xl font-bold mb-1">
                @if($loyaltyPoints && $loyaltyPoints->getTier())
                    {{ config('loyalty.tiers.' . $loyaltyPoints->getTier(), 'Standard') }}
                @else
                    Standard
                @endif
            </p>
            <div class="text-xs text-slate-400">
                @if($loyaltyPoints)
                    <p>{{ $loyaltyPoints->total_points ?? 0 }} pts total</p>
                    @php
                        $nextTierPoints = config('loyalty.tier_thresholds.' . ($loyaltyPoints->getTier() === 'platinum' ? 'platinum' : 'next'));
                        $remaining = max(0, ($nextTierPoints ?? 0) - ($loyaltyPoints->total_points ?? 0));
                    @endphp
                    @if($remaining > 0)
                        <p>{{ $remaining }} pts avant prochain niveau</p>
                    @endif
                @endif
            </div>
        </div>

        <div class="glass p-6 border border-green-400/20">
            <p class="text-slate-400 text-sm mb-2">Réductions Actives</p>
            <p class="text-2xl font-bold text-green-400 mb-1">
                @if($loyaltyPoints)
                    @php
                        $discountPercent = [
                            'standard' => 0,
                            'silver' => 5,
                            'gold' => 10,
                            'platinum' => 15
                        ];
                        $tier = $loyaltyPoints->getTier() ?? 'standard';
                        $discount = $discountPercent[$tier] ?? 0;
                    @endphp
                    -{{ $discount }}%
                @else
                    0%
                @endif
            </p>
            <p class="text-xs text-slate-500">Sur tous vos abonnements</p>
        </div>
    </div>

    <!-- Niveaux de Fidélité -->
    <div class="glass p-8">
        <h2 class="font-bold text-xl mb-6">Niveaux de Fidélité</h2>

        <div class="space-y-4">
            <!-- Standard -->
            <div class="p-4 rounded-lg {{ $loyaltyPoints && $loyaltyPoints->getTier() === 'standard' ? 'bg-indigo-500/10 border border-indigo-500/20' : 'bg-white/5 border border-white/10' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold">🌟 Standard</p>
                        <p class="text-xs text-slate-400">Votre niveau de départ</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold">0 - 999 pts</p>
                        <p class="text-xs text-slate-400">Réduction: 0%</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-slate-300">
                    • 1 point pour 1€ dépensé • Accès au catalogue
                </div>
            </div>

            <!-- Silver -->
            <div class="p-4 rounded-lg {{ $loyaltyPoints && $loyaltyPoints->getTier() === 'silver' ? 'bg-indigo-500/10 border border-indigo-500/20' : 'bg-white/5 border border-white/10' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold">⭐ Silver</p>
                        <p class="text-xs text-slate-400">Fidèle client</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold">1000 - 4999 pts</p>
                        <p class="text-xs text-slate-400">Réduction: 5%</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-slate-300">
                    • 1.05 points pour 1€ • Livraison gratuite • Accès des offres exclusives
                </div>
            </div>

            <!-- Gold -->
            <div class="p-4 rounded-lg {{ $loyaltyPoints && $loyaltyPoints->getTier() === 'gold' ? 'bg-indigo-500/10 border border-indigo-500/20' : 'bg-white/5 border border-white/10' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold">💛 Gold</p>
                        <p class="text-xs text-slate-400">Client VIP</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold">5000 - 14999 pts</p>
                        <p class="text-xs text-slate-400">Réduction: 10%</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-slate-300">
                    • 1.1 points pour 1€ • Livraison gratuite illimitée • Client service prioritaire • Accès early access aux nouvelles box
                </div>
            </div>

            <!-- Platinum -->
            <div class="p-4 rounded-lg {{ $loyaltyPoints && $loyaltyPoints->getTier() === 'platinum' ? 'bg-indigo-500/10 border border-indigo-500/20' : 'bg-white/5 border border-white/10' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold">👑 Platinum</p>
                        <p class="text-xs text-slate-400">Client d'élite</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold">15000+ pts</p>
                        <p class="text-xs text-slate-400">Réduction: 15%</p>
                    </div>
                </div>
                <div class="mt-2 text-xs text-slate-300">
                    • 1.15 points pour 1€ • Livraison express gratuite • Client service VIP 24/7 • Cadeaux surprise réguliers
                </div>
            </div>
        </div>
    </div>

    <!-- Utiliser les Points -->
    @if($loyaltyPoints && $loyaltyPoints->balance > 0)
        <div class="glass p-8">
            <h2 class="font-bold text-xl mb-6">
                <i class="fas fa-gift mr-2 text-yellow-400"></i>
                Utiliser mes Points
            </h2>

            <div class="space-y-3">
                <div class="p-4 rounded-lg bg-white/5 border border-white/10 hover:border-indigo-500/30 transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">Crédit de {{ number_format(floor($loyaltyPoints->balance / 10) * 10 / 10, 2, ',', ' ') }}€</p>
                            <p class="text-xs text-slate-400">{{ $loyaltyPoints->balance }} points = {{ number_format($loyaltyPoints->balance / 10, 2, ',', ' ') }}€</p>
                        </div>
                        <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded text-sm font-semibold transition">
                            Utiliser
                        </button>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-white/5 border border-white/10 hover:border-indigo-500/30 transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">Réduction 10% - 500 points</p>
                            <p class="text-xs text-slate-400">
                                @if($loyaltyPoints->balance >= 500)
                                    <span class="text-green-400">✓ Disponible</span>
                                @else
                                    <span class="text-amber-400">{{ 500 - $loyaltyPoints->balance }} points manquants</span>
                                @endif
                            </p>
                        </div>
                        <button {{ $loyaltyPoints->balance >= 500 ? '' : 'disabled' }} class="px-4 py-2 {{ $loyaltyPoints->balance >= 500 ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-slate-600 cursor-not-allowed' }} rounded text-sm font-semibold transition">
                            Réclamer
                        </button>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-white/5 border border-white/10 hover:border-indigo-500/30 transition cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">Livraison Gratuite - 1000 points</p>
                            <p class="text-xs text-slate-400">
                                @if($loyaltyPoints->balance >= 1000)
                                    <span class="text-green-400">✓ Disponible</span>
                                @else
                                    <span class="text-amber-400">{{ 1000 - $loyaltyPoints->balance }} points manquants</span>
                                @endif
                            </p>
                        </div>
                        <button {{ $loyaltyPoints->balance >= 1000 ? '' : 'disabled' }} class="px-4 py-2 {{ $loyaltyPoints->balance >= 1000 ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-slate-600 cursor-not-allowed' }} rounded text-sm font-semibold transition">
                            Réclamer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="glass p-8 text-center">
            <i class="fas fa-gift text-4xl text-slate-500 mb-4 block"></i>
            <p class="text-slate-400 mb-4">Vous n'avez aucun point à utiliser</p>
            <p class="text-sm text-slate-500">Commencez à acheter pour accumulation des points</p>
        </div>
    @endif

    <!-- Historique -->
    <div class="glass p-8">
        <h2 class="font-bold text-xl mb-6">
            <i class="fas fa-history mr-2"></i>
            Historique des Points
        </h2>

        @if($loyaltyPoints && $loyaltyPoints->pointsHistory && count($loyaltyPoints->pointsHistory) > 0)
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @foreach($loyaltyPoints->pointsHistory as $entry)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-white/5">
                        <div>
                            <p class="text-sm font-semibold">{{ $entry['description'] ?? 'Opération' }}</p>
                            <p class="text-xs text-slate-400">{{ $entry['date'] ?? now()->format('d/m/Y') }}</p>
                        </div>
                        <span class="text-sm font-bold {{ str_contains($entry['type'] ?? '', 'credit') ? 'text-green-400' : 'text-red-400' }}">
                            {{ str_contains($entry['type'] ?? '', 'credit') ? '+' : '-' }}{{ abs($entry['amount'] ?? 0) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center bg-white/5 rounded-lg">
                <i class="fas fa-inbox text-2xl text-slate-500 mb-2 block"></i>
                <p class="text-slate-400">Aucune opération sur vos points</p>
            </div>
        @endif
    </div>

    <!-- FAQ -->
    <div class="glass p-8">
        <h2 class="font-bold text-xl mb-6">
            <i class="fas fa-question-circle mr-2"></i>
            Questions Fréquentes
        </h2>

        <div class="space-y-4">
            <div>
                <p class="font-semibold mb-2">Comment gagner des points?</p>
                <p class="text-sm text-slate-300">Vous gagnez 1 point pour chaque euro dépensé. Les bonus varient selon votre niveau de fidélité.</p>
            </div>
            <div>
                <p class="font-semibold mb-2">Comment utiliser mes points?</p>
                <p class="text-sm text-slate-300">Vous pouvez convertir vos points en crédit (1pt = 0.1€) ou les échanger contre des récompenses.</p>
            </div>
            <div>
                <p class="font-semibold mb-2">Mes points expirent-ils?</p>
                <p class="text-sm text-slate-300">Non, vos points n'expirent jamais. Ils restent disponibles indéfiniment.</p>
            </div>
        </div>
    </div>
</div>
@endsection
