@extends('layouts.app')

@section('title', 'Admin Dashboard - LynBox')

@section('content')
<div class="space-y-8">
    <header>
        <h1 class="text-3xl font-bold">Tableau de Bord Admin</h1>
        <p class="text-slate-400">Gestion et analytics de votre marketplace</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass p-6">
            <p class="text-slate-400 text-sm font-medium">Utilisateurs</p>
            <h2 class="text-4xl font-bold mt-2">{{ $totalUsers }}</h2>
            <p class="text-slate-500 text-xs mt-2">Clients actifs</p>
        </div>
        <div class="glass p-6">
            <p class="text-slate-400 text-sm font-medium">Abonnements Actifs</p>
            <h2 class="text-4xl font-bold mt-2">{{ $activeSubscriptions }}</h2>
            <p class="text-green-400 text-xs mt-2 flex items-center">
                <i class="fas fa-arrow-up mr-1"></i>
                +{{ intval($activeSubscriptions * 0.15) }} ce mois
            </p>
        </div>
        <div class="glass p-6">
            <p class="text-slate-400 text-sm font-medium">Chiffre d'Affaires</p>
            <h2 class="text-4xl font-bold mt-2 text-indigo-400">{{ number_format($totalRevenue, 0, ',', ' ') }}€</h2>
            <p class="text-slate-500 text-xs mt-2">Total généré</p>
        </div>
        <div class="glass p-6">
            <p class="text-slate-400 text-sm font-medium">Taux de Churn</p>
            <h2 class="text-4xl font-bold mt-2 text-red-400">{{ number_format($churnRate, 1) }}%</h2>
            <p class="text-slate-500 text-xs mt-2">Mois précédent</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <section class="glass p-8">
            <h2 class="text-xl font-bold mb-6">Revenus Mensuels</h2>
            <div class="space-y-4">
                @foreach($monthlyRevenue['months'] as $index => $month)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">{{ $month }}</span>
                        <div class="flex items-center space-x-3 flex-1 ml-4">
                            <div class="h-2 bg-white/10 flex-1 rounded-full">
                                @php
                                    $maxRevenue = max($monthlyRevenue['revenues']);
                                    $percentage = $maxRevenue > 0 ? intval(($monthlyRevenue['revenues'][$index] / $maxRevenue) * 100) : 0;
                                @endphp
                                <div 
                                    class="h-2 bg-indigo-500 rounded-full transition" 
                                    style="width: {{ min(100, $percentage) }}%"
                                ></div>
                            </div>
                            <span class="text-sm font-semibold">{{ number_format($monthlyRevenue['revenues'][$index], 0, ',', ' ') }}€</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="glass p-8">
            <h2 class="text-xl font-bold mb-6">Top 5 Boxes</h2>
            <div class="space-y-4">
                @foreach($topBoxes as $box)
                    <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                        <div>
                            <p class="font-semibold">{{ $box['title'] }}</p>
                            <p class="text-xs text-slate-500">{{ $box['subscriptions'] }} abonnements</p>
                        </div>
                        <span class="text-indigo-400 font-bold">{{ number_format($box['revenue'], 0, ',', ' ') }}€</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <section class="glass p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Abonnements Récents</h2>
            <a href="{{ route('admin.boxes.index') }}" class="text-indigo-400 hover:text-indigo-300 text-sm">Voir plus</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 text-slate-400 font-semibold">Utilisateur</th>
                        <th class="text-left py-3 text-slate-400 font-semibold">Box</th>
                        <th class="text-left py-3 text-slate-400 font-semibold">Statut</th>
                        <th class="text-left py-3 text-slate-400 font-semibold">Renouvellement</th>
                        <th class="text-left py-3 text-slate-400 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSubscriptions as $subscription)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition">
                            <td class="py-3">{{ $subscription->user->name }}</td>
                            <td class="py-3">{{ $subscription->box->title }}</td>
                            <td class="py-3">
                                <span class="status-badge {{ $subscription->status === 'active' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-slate-400">{{ $subscription->next_renewal_date->format('d M Y') }}</td>
                            <td class="py-3 text-slate-400">{{ $subscription->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
