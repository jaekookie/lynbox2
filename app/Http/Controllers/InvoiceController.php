<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = auth()->user()->invoices()
            ->with('subscription.box')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load('subscription.user', 'subscription.box');

        return view('invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        if (!$invoice->pdf_path || !file_exists(storage_path('app/' . $invoice->pdf_path))) {
            return response()->json([
                'message' => 'Le fichier PDF n\'est pas disponible.',
            ], 404);
        }

        return response()->download(
            storage_path('app/' . $invoice->pdf_path),
            "{$invoice->invoice_number}.pdf"
        );
    }

    public function retry(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        if ($invoice->isPaid()) {
            return response()->json([
                'message' => 'Cette facture a déjà été payée.',
            ], 422);
        }

        $paymentService = new \App\Services\PaymentService();

        if ($paymentService->retryFailedInvoice($invoice)) {
            return response()->json([
                'message' => 'Paiement retraité avec succès.',
                'invoice' => $invoice,
            ]);
        }

        return response()->json([
            'message' => 'Le paiement a échoué. Veuillez vérifier votre moyen de paiement.',
        ], 422);
    }
}
