@extends('layouts.app')

@section('title', 'Gestion des Boxes - LynBox')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">Gestion des Boxes</h1>
            <p class="text-slate-400 mt-2">Créez, modifiez et supprimez vos boxes</p>
        </div>
        <a href="{{ route('admin.boxes.create') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
            <i class="fas fa-plus mr-2"></i> Nouvelle Box
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="glass p-8 rounded-2xl overflow-x-auto">
        @if($boxes->count() > 0)
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4 font-semibold">Titre</th>
                        <th class="text-left py-3 px-4 font-semibold">Catégorie</th>
                        <th class="text-left py-3 px-4 font-semibold">Prix</th>
                        <th class="text-left py-3 px-4 font-semibold">Stock</th>
                        <th class="text-left py-3 px-4 font-semibold">Fréquence</th>
                        <th class="text-left py-3 px-4 font-semibold">Statut</th>
                        <th class="text-left py-3 px-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($boxes as $box)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    @if($box->image_url)
                                        <img src="{{ $box->image_url }}" alt="{{ $box->title }}" class="h-10 w-10 rounded-lg object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-indigo-500/20 flex items-center justify-center text-lg">
                                            {{ $box->emoji ?? '📦' }}
                                        </div>
                                    @endif
                                    <span class="font-semibold">{{ $box->title }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-indigo-400">{{ $box->category->name }}</span>
                            </td>
                            <td class="py-4 px-4 font-semibold">{{ number_format($box->price, 2, ',', ' ') }}€</td>
                            <td class="py-4 px-4">
                                <span class="{{ $box->stock_quantity > 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $box->stock_quantity }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-xs bg-white/10 px-3 py-1 rounded-full">
                                    @if($box->billing_cycle === 'monthly')
                                        📅 Mensuel
                                    @elseif($box->billing_cycle === 'quarterly')
                                        📅 Trimestriel
                                    @else
                                        📅 Annuel
                                    @endif
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-xs {{ $box->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }} px-3 py-1 rounded-full">
                                    {{ $box->is_active ? '✓ Actif' : '✗ Inactif' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.boxes.edit', $box) }}" class="px-3 py-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg text-xs transition">
                                        <i class="fas fa-edit mr-1"></i> Éditer
                                    </a>
                                    <form action="{{ route('admin.boxes.destroy', $box) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg text-xs transition">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($boxes->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $boxes->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-5xl text-slate-400 mb-4"></i>
                <p class="text-slate-400">Aucune box trouvée.</p>
                <a href="{{ route('admin.boxes.create') }}" class="mt-4 inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-plus mr-2"></i> Créer la première box
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
