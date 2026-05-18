@extends('layouts.app')

@section('title', 'Livraisons - LynBox')

@section('content')
<div class="space-y-8">
    <header>
        <h1 class="text-3xl font-bold">Mes Livraisons</h1>
        <p class="text-slate-400">Suivi de vos colis en temps réel</p>
    </header>

    <section>
        @if($deliveries->isEmpty())
            <div class="glass p-8 text-center">
                <i class="fas fa-inbox text-5xl text-slate-400 mb-4"></i>
                <p class="text-slate-400 mb-4">Vous n'avez pas de livraisons pour le moment.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($deliveries as $delivery)
                    <a href="{{ route('deliveries.show', $delivery) }}" class="glass p-6 hover:border-indigo-500/40 transition flex flex-col md:flex-row items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="h-12 w-12 bg-indigo-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-box text-indigo-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">{{ $delivery->subscription->box->title }}</h3>
                                <p class="text-xs text-slate-500">{{ $delivery->tracking_number }}</p>
                            </div>
                        </div>

                        <div class="flex-1 mx-4 hidden md:block">
                            <div class="flex items-center space-x-2 mb-1">
                                @if($delivery->isPreparing())
                                    <span class="text-sm font-semibold text-amber-400">En préparation</span>
                                @elseif($delivery->isShipped())
                                    <span class="text-sm font-semibold text-blue-400">Expédié</span>
                                @elseif($delivery->isDelivered())
                                    <span class="text-sm font-semibold text-green-400">Livré</span>
                                @endif
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full transition" style="width: {{ $delivery->progressPercentage() }}%"></div>
                            </div>
                        </div>

                        <div class="text-right md:text-right mt-4 md:mt-0">
                            <p class="text-sm text-slate-400">
                                @if($delivery->estimated_delivery)
                                    Livraison prévue
                                    <br>
                                    <span class="font-semibold">{{ $delivery->estimated_delivery->format('d M Y') }}</span>
                                @else
                                    <span class="text-yellow-400">En cours</span>
                                @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
