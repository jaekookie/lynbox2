<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceGenerator
{
    public function generateInvoicePDF(Invoice $invoice): string
    {
        $invoice->load('subscription.user', 'subscription.box');

        $pdf = Pdf::loadView('invoices.template', compact('invoice'))
            ->setPaper('a4')
            ->setOption('isRemoteEnabled', true);

        $fileName = $invoice->invoice_number . '.pdf';
        $path = 'invoices/' . date('Y/m/') . $fileName;

        \Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    public function saveInvoicePath(Invoice $invoice): void
    {
        $pdfPath = $this->generateInvoicePDF($invoice);
        $invoice->update(['pdf_path' => $pdfPath]);
    }
}
