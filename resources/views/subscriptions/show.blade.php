@extends('layouts.app')

@section('title', 'Détails Abonnement - LynBox')

@section('content')
<div class="space-y-8">
    <header class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold">{{ $subscription->box->title }}</h1>
            <p class="text-slate-400">Détails de votre abonnement</p>
        </div>
        <span class="status-badge {{ $subscription->status === 'active' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
            {{ ucfirst($subscription->status) }}
        </span>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <section class="glass p-8">
            <h2 class="text-xl font-bold mb-6">Informations de l'abonnement</h2>

            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-slate-400">Box</span>
                    <span class="font-semibold">{{ $subscription->box->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Catégorie</span>
                    <span class="font-semibold">{{ $subscription->box->category->name }}</span>
                </div>
                <div class="flex justify-between border-t border-white/10 pt-4">
                    <span class="text-slate-400">Prix mensuel</span>
                    <span class="font-bold text-indigo-400">{{ number_format($subscription->current_price, 2, ',', ' ') }}€</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Fréquence</span>
                    <span class="font-semibold">{{ ucfirst(__('pagination.' . $subscription->box->billing_cycle)) }}</span>
                </div>
                <div class="flex justify-between border-t border-white/10 pt-4">
                    <span class="text-slate-400">Prochain renouvellement</span>
                    <span class="font-semibold">{{ $subscription->next_renewal_date->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Abonné depuis</span>
                    <span class="font-semibold">{{ $subscription->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <div class="flex space-x-3 mt-8 pt-8 border-t border-white/10">
                @if($subscription->isActive())
                    <button class="flex-1 px-4 py-2 bg-white/5 hover:bg-white/10 rounded-lg text-sm transition">
                        Mettre en pause
                    </button>
                    <button class="flex-1 px-4 py-2 bg-red-500/20 text-red-400 hover:bg-red-500/30 rounded-lg text-sm font-semibold transition">
                        Annuler l'abonnement
                    </button>
                @else
                    <button class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold transition">
                        Réactiver
                    </button>
                @endif
            </div>
        </section>

        <section class="glass p-8">
            <h2 class="text-xl font-bold mb-6">Dernières livraisons</h2>

            @if($subscription->deliveries->isEmpty())
                <p class="text-slate-400">Aucune livraison pour le moment.</p>
            @else
                <div class="space-y-4">
                    @foreach($subscription->deliveries as $delivery)
                        <a href="{{ route('deliveries.show', $delivery) }}" class="p-4 bg-white/5 rounded-lg hover:bg-white/10 transition">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm {{ $delivery->isDelivered() ? 'text-green-400' : 'text-slate-400' }}">
                                    {{ $delivery->getStatusLabel() }}
                                </span>
                                <span class="text-xs text-slate-500">{{ $delivery->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="text-xs text-slate-500">
                                Suivi: {{ $delivery->tracking_number }}
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1 mt-2">
                                <div class="bg-indigo-500 h-1 rounded-full transition" style="width: {{ $delivery->progressPercentage() }}%"></div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <section class="glass p-8">
        <h2 class="text-xl font-bold mb-6">Historique des paiements</h2>

        @if($subscription->invoices->isEmpty())
            <p class="text-slate-400">Aucune facture pour le moment.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-left py-3 text-slate-400 font-semibold">Facture</th>
                            <th class="text-left py-3 text-slate-400 font-semibold">Montant</th>
                            <th class="text-left py-3 text-slate-400 font-semibold">Statut</th>
                            <th class="text-left py-3 text-slate-400 font-semibold">Date</th>
                            <th class="text-right py-3 text-slate-400 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscription->invoices as $invoice)
                            <tr class="border-b border-white/5">
                                <td class="py-3">{{ $invoice->invoice_number }}</td>
                                <td class="py-3 text-indigo-400 font-semibold">{{ number_format($invoice->amount, 2, ',', ' ') }}€</td>
                                <td class="py-3">
                                    <span class="status-badge {{ $invoice->isPaid() ? 'bg-green-500/10 text-green-400 border border-green-500/20' : ($invoice->isFailed() ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="py-3 text-slate-400">{{ $invoice->created_at->format('d M Y') }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-400 hover:text-indigo-300 text-sm">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
