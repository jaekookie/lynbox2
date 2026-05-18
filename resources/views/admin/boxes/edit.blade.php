@extends('layouts.app')

@section('title', 'Éditer une Box - LynBox')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.boxes.index') }}" class="text-indigo-400 hover:text-indigo-300 transition">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
        <h1 class="text-3xl font-bold">Éditer: {{ $box->title }}</h1>
    </div>

    <div class="glass p-8 rounded-2xl max-w-2xl">
        <form action="{{ route('admin.boxes.update', $box) }}" method="POST" class="space-y-6">
            @csrf @method('PATCH')

            <div>
                <label class="block text-sm font-semibold mb-2">Titre de la Box *</label>
                <input type="text" name="title" value="{{ old('title', $box->title) }}" placeholder="Ex: Summer Glow Beauty Box"
                    class="w-full bg-white/5 border {{ $errors->has('title') ? 'border-red-500' : 'border-white/10' }} rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition" required>
                @error('title')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2">Description *</label>
                <textarea name="description" placeholder="Décrivez votre box en détail..." rows="4"
                    class="w-full bg-white/5 border {{ $errors->has('description') ? 'border-red-500' : 'border-white/10' }} rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition resize-none" required>{{ old('description', $box->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Catégorie *</label>
                    <select name="category_id" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-indigo-500/50 focus:outline-none transition" required>
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $box->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Prix *</label>
                    <div class="relative">
                        <input type="number" name="price" value="{{ old('price', $box->price) }}" placeholder="29.99" step="0.01" min="0"
                            class="w-full bg-white/5 border {{ $errors->has('price') ? 'border-red-500' : 'border-white/10' }} rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition pr-8" required>
                        <span class="absolute right-3 top-3 text-slate-400">€</span>
                    </div>
                    @error('price')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Stock *</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $box->stock_quantity) }}" min="0"
                        class="w-full bg-white/5 border {{ $errors->has('stock_quantity') ? 'border-red-500' : 'border-white/10' }} rounded-lg px-4 py-3 text-white placeholder-slate-500 focus:border-indigo-500/50 focus:outline-none transition" required>
                    @error('stock_quantity')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Fréquence *</label>
                    <select name="billing_cycle" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-indigo-500/50 focus:outline-none transition" required>
                        <option value="monthly" {{ old('billing_cycle', $box->billing_cycle) == 'monthly' ? 'selected' : '' }}>📅 Mensuel</option>
                        <option value="quarterly" {{ old('billing_cycle', $box->billing_cycle) == 'quarterly' ? 'selected' : '' }}>📅 Trimestriel</option>
                        <option value="yearly" {{ old('billing_cycle', $box->billing_cycle) == 'yearly' ? 'selected' : '' }}>📅 Annuel</option>
                    </select>
                    @error('billing_cycle')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-white/5 cursor-pointer transition">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $box->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded accent-indigo-500">
                    <span class="font-semibold">Box active</span>
                </label>
            </div>

            <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                <p class="text-sm text-blue-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    Créée le: {{ $box->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-lg font-semibold transition">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
                <a href="{{ route('admin.boxes.index') }}" class="flex-1 py-3 bg-white/10 hover:bg-white/20 rounded-lg font-semibold text-center transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
