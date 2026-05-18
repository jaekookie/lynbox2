@extends('layouts.app')

@section('title', 'Historique Factures - LynBox')

@section('content')
<div class="space-y-8">
    <header>
        <h1 class="text-3xl font-bold">Mes Factures</h1>
        <p class="text-slate-400">Historique de vos paiements et factures</p>
    </header>

    <section class="glass p-8">
        @if($invoices->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-file-invoice text-5xl text-slate-400 mb-4"></i>
                <p class="text-slate-400">Aucune facture pour le moment.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-left py-4 text-slate-400 font-semibold">Numéro de facture</th>
                            <th class="text-left py-4 text-slate-400 font-semibold">Box</th>
                            <th class="text-left py-4 text-slate-400 font-semibold">Montant</th>
                            <th class="text-left py-4 text-slate-400 font-semibold">Statut</th>
                            <th class="text-left py-4 text-slate-400 font-semibold">Date</th>
                            <th class="text-right py-4 text-slate-400 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition">
                                <td class="py-4 font-mono text-sm">{{ $invoice->invoice_number }}</td>
                                <td class="py-4">{{ $invoice->subscription->box->title }}</td>
                                <td class="py-4 text-indigo-400 font-bold">{{ number_format($invoice->amount, 2, ',', ' ') }}€</td>
                                <td class="py-4">
                                    @if($invoice->isPaid())
                                        <span class="status-badge bg-green-500/10 text-green-400 border border-green-500/20">Payée</span>
                                    @elseif($invoice->isFailed())
                                        <span class="status-badge bg-red-500/10 text-red-400 border border-red-500/20">Échouée</span>
                                    @elseif($invoice->isRefunded())
                                        <span class="status-badge bg-blue-500/10 text-blue-400 border border-blue-500/20">Remboursée</span>
                                    @else
                                        <span class="status-badge bg-amber-500/10 text-amber-400 border border-amber-500/20">En attente</span>
                                    @endif
                                </td>
                                <td class="py-4 text-slate-400">{{ $invoice->created_at->format('d M Y') }}</td>
                                <td class="py-4 text-right space-x-2">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-400 hover:text-indigo-300 text-sm">Voir</a>
                                    @if($invoice->pdf_path)
                                        <a href="{{ route('invoices.download', $invoice) }}" class="text-indigo-400 hover:text-indigo-300 text-sm">PDF</a>
                                    @endif
                                    @if($invoice->isFailed())
                                        <button onclick="retryPayment({{ $invoice->id }})" class="text-yellow-400 hover:text-yellow-300 text-sm">Réessayer</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $invoices->links() }}
            </div>
        @endif
    </section>
</div>

<script>
function retryPayment(invoiceId) {
    if (confirm('Êtes-vous sûr de vouloir réessayer ce paiement?')) {
        // Implementation will be added
    }
}
</script>
@endsection
