<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Services\CashierService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private CashierService $cashierService;
    private PaymentService $paymentService;

    public function __construct(CashierService $cashierService, PaymentService $paymentService)
    {
        $this->cashierService = $cashierService;
        $this->paymentService = $paymentService;
    }

    public function setupIntent(Request $request)
    {
        $user = auth()->user();

        if (!$user->stripe_id) {
            $user->update(['stripe_id' => $this->cashierService->createCustomer($user)]);
        }

        $intent = $this->cashierService->createPaymentIntent(
            $user,
            $request->amount ?? 0
        );

        return response()->json($intent);
    }

    public function attachPaymentMethod(StorePaymentMethodRequest $request)
    {
        $user = auth()->user();

        if (!$user->stripe_id) {
            $user->update(['stripe_id' => $this->cashierService->createCustomer($user)]);
        }

        if ($this->paymentService->createPaymentMethod($user, $request->payment_method_id)) {
            return response()->json([
                'message' => 'Moyen de paiement ajouté avec succès.',
            ]);
        }

        return response()->json([
            'message' => 'Une erreur s\'est produite lors de l\'ajout du moyen de paiement.',
        ], 422);
    }

    public function deletePaymentMethod(Request $request)
    {
        $user = auth()->user();

        if ($this->paymentService->deletePaymentMethod($user, $request->payment_method_id)) {
            return response()->json([
                'message' => 'Moyen de paiement supprimé avec succès.',
            ]);
        }

        return response()->json([
            'message' => 'Une erreur s\'est produite.',
        ], 422);
    }

    public function setDefaultPaymentMethod(Request $request)
    {
        $user = auth()->user();

        if ($this->paymentService->setDefaultPaymentMethod($user, $request->payment_method_id)) {
            return response()->json([
                'message' => 'Moyen de paiement par défaut mis à jour.',
            ]);
        }

        return response()->json([
            'message' => 'Une erreur s\'est produite.',
        ], 422);
    }
}
