@extends('layouts.app')

@section('title', 'Détails Facture - LynBox')

@section('content')
<div class="space-y-8">
    <a href="{{ route('invoices.index') }}" class="flex items-center text-indigo-400 hover:text-indigo-300 mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux factures
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="glass p-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h1 class="text-3xl font-bold">Facture</h1>
                        <p class="text-slate-400">{{ $invoice->invoice_number }}</p>
                    </div>
                    <span class="status-badge {{ $invoice->isPaid() ? 'bg-green-500/10 text-green-400 border border-green-500/20' : ($invoice->isFailed() ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>

                <div class="border-t border-white/10 pt-8 mb-8">
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Facturé à</p>
                            <div class="font-semibold">
                                <p>{{ $invoice->subscription->user->name }}</p>
                                <p class="text-sm text-slate-400">{{ $invoice->subscription->user->email }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Détails</p>
                            <div class="space-y-1 text-sm">
                                <div>
                                    <span class="text-slate-400">Date de facture:</span>
                                    <span class="font-semibold ml-2">{{ $invoice->created_at->format('d M Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400">Date d'échéance:</span>
                                    <span class="font-semibold ml-2">{{ $invoice->created_at->addDays(30)->format('d M Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400">Payée le:</span>
                                    <span class="font-semibold ml-2">{{ $invoice->paid_at?->format('d M Y') ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-8">
                    <table class="w-full mb-8">
                        <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left py-3 text-slate-400 font-semibold">Description</th>
                                <th class="text-right py-3 text-slate-400 font-semibold">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-white/10">
                                <td class="py-4">
                                    <p class="font-semibold">{{ $invoice->subscription->box->title }}</p>
                                    <p class="text-xs text-slate-500">
                                        @if($invoice->subscription->box->billing_cycle === 'monthly')
                                            Abonnement mensuel
                                        @elseif($invoice->subscription->box->billing_cycle === 'quarterly')
                                            Abonnement trimestriel
                                        @else
                                            Abonnement annuel
                                        @endif
                                    </p>
                                </td>
                                <td class="py-4 text-right font-bold text-indigo-400">
                                    {{ number_format($invoice->amount, 2, ',', ' ') }}€
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex justify-end mb-8">
                        <div class="w-64">
                            <div class="flex justify-between py-2 border-t border-white/10 mb-2">
                                <span class="text-slate-400">Sous-total</span>
                                <span class="font-semibold">{{ number_format($invoice->amount, 2, ',', ' ') }}€</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-white/10 mb-4">
                                <span class="text-slate-400">TVA</span>
                                <span class="font-semibold">0€</span>
                            </div>
                            <div class="flex justify-between py-2 text-lg">
                                <span class="font-bold">Total TTC</span>
                                <span class="font-bold text-indigo-400">{{ number_format($invoice->amount, 2, ',', ' ') }}€</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($invoice->notes)
                    <div class="border-t border-white/10 pt-8">
                        <p class="text-slate-400 text-sm mb-2">Notes</p>
                        <p class="text-slate-300">{{ $invoice->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="glass p-6">
                <h3 class="font-bold mb-4">Actions</h3>
                <div class="space-y-2">
                    @if($invoice->pdf_path)
                        <a href="{{ route('invoices.download', $invoice) }}" class="block text-center py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold transition">
                            <i class="fas fa-download mr-2"></i> Télécharger PDF
                        </a>
                    @endif

                    @if($invoice->isFailed())
                        <button onclick="retryPayment()" class="block w-full py-2 bg-yellow-600/20 hover:bg-yellow-600/30 text-yellow-400 rounded-lg text-sm font-semibold transition">
                            <i class="fas fa-redo mr-2"></i> Réessayer
                        </button>
                    @endif

                    <button onclick="printInvoice()" class="block w-full py-2 bg-white/5 hover:bg-white/10 rounded-lg text-sm transition">
                        <i class="fas fa-print mr-2"></i> Imprimer
                    </button>
                </div>
            </div>

            <div class="glass p-6">
                <h3 class="font-bold mb-4">Informations</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-slate-400 mb-1">Numéro Stripe</p>
                        <p class="font-mono text-xs break-all">{{ $invoice->stripe_invoice_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-1">Abonnement</p>
                        <a href="{{ route('subscriptions.show', $invoice->subscription) }}" class="text-indigo-400 hover:text-indigo-300">
                            {{ $invoice->subscription->box->title }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="glass p-6">
                <h3 class="font-bold mb-4">Support</h3>
                <p class="text-xs text-slate-400 mb-4">Question sur cette facture?</p>
                <a href="#" class="text-indigo-400 hover:text-indigo-300 text-sm">
                    Contacter le support <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function retryPayment() {
    if (confirm('Êtes-vous sûr de vouloir relancer le paiement?')) {
        fetch('{{ route("invoices.retry", $invoice) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.invoice) window.location.reload();
        })
        .catch(error => {
            alert('Une erreur s\'est produite');
            console.error('Error:', error);
        });
    }
}

function printInvoice() {
    window.print();
}
</script>
@endsection
