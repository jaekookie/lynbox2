<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LynBox - Marketplace de Box Mensuelles')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top left, #0f172a, #020617);
            color: #f8fafc;
            min-height: 100vh;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
        }

        .gradient-text {
            background: linear-gradient(135deg, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .box-card:hover {
            transform: translateY(-5px);
            border-color: rgba(129, 140, 248, 0.4);
            transition: all 0.3s ease;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .active-dot {
            height: 8px;
            width: 8px;
            background-color: #34d399;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 10px #34d399;
        }
    </style>
    @stack('styles')
</head>
<body class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Mobile: Top Navigation Bar -->
        <div class="lg:hidden mb-6 glass p-4 rounded-xl flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="h-8 w-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box-open text-white text-sm"></i>
                </div>
                <span class="font-bold">Lyn<span class="gradient-text">Cha</span></span>
            </div>
            <button id="mobileMenuToggle" class="p-2 hover:bg-white/10 rounded-lg transition">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile: Collapsible Account Card -->
        <div class="lg:hidden mb-6 glass p-4 rounded-xl hidden" id="mobileAccountCard">
            <div class="flex items-center space-x-3 mb-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=818cf8&color=fff" class="h-12 w-12 rounded-lg" alt="Avatar">
                <div class="flex-1">
                    <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-indigo-400">👑 {{ ucfirst(auth()->user()->membership_tier) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('account.settings') }}" class="py-2 px-3 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-400/20 rounded-lg text-xs transition text-indigo-300 text-center">
                    <i class="fas fa-user-circle mr-1"></i>Profil
                </a>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2 px-3 bg-red-500/10 hover:bg-red-500/20 border border-red-400/20 rounded-lg text-xs transition text-red-300">
                        <i class="fas fa-sign-out-alt mr-1"></i>Quitter
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile: Collapsible Menu -->
        <nav class="lg:hidden mb-6 glass p-3 rounded-xl space-y-2 hidden" id="mobileMenu">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-2 text-sm {{ Request::routeIs('dashboard') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white' }} rounded-lg transition">
                <i class="fas fa-th-large w-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('catalog.index') }}" class="flex items-center space-x-3 p-2 text-sm {{ Request::routeIs('catalog.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white' }} rounded-lg transition">
                <i class="fas fa-shopping-bag w-5"></i>
                <span>Catalogue</span>
            </a>
            <a href="{{ route('deliveries.index') }}" class="flex items-center space-x-3 p-2 text-sm {{ Request::routeIs('deliveries.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white' }} rounded-lg transition">
                <i class="fas fa-truck w-5"></i>
                <span>Livraisons</span>
            </a>
            <a href="{{ route('reviews.index') }}" class="flex items-center space-x-3 p-2 text-sm {{ Request::routeIs('reviews.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white' }} rounded-lg transition">
                <i class="fas fa-star w-5"></i>
                <span>Mes Avis</span>
            </a>
            <a href="{{ route('account.settings') }}" class="flex items-center space-x-3 p-2 text-sm text-slate-400 hover:text-white rounded-lg transition">
                <i class="fas fa-cog w-5"></i>
                <span>Paramètres</span>
            </a>
            @if(auth()->user()->role === 'admin')
                <hr class="border-white/10 my-2">
                <div class="text-xs font-semibold text-indigo-400 px-2">👑 ADMIN</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-2 text-sm {{ Request::routeIs('admin.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white' }} rounded-lg transition">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.boxes.index') }}" class="flex items-center space-x-3 p-2 text-sm text-slate-400 hover:text-white rounded-lg transition">
                    <i class="fas fa-cubes w-5"></i>
                    <span>Gestion Boxes</span>
                </a>
            @endif
        </nav>

        <!-- Desktop Layout -->
        <div class="hidden lg:grid grid-cols-12 gap-8">
            <aside class="col-span-3 space-y-6">
                <div class="flex items-center space-x-3 px-4">
                    <div class="h-10 w-10 bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-box-open text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">Lyn<span class="gradient-text">Cha</span></span>
                </div>

                <nav class="glass p-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 {{ Request::routeIs('dashboard') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl font-semibold transition">
                        <i class="fas fa-th-large w-6"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('catalog.index') }}" class="flex items-center space-x-3 p-3 {{ Request::routeIs('catalog.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl font-semibold transition">
                        <i class="fas fa-shopping-bag w-6"></i>
                        <span>Catalogue</span>
                    </a>
                    <a href="{{ route('deliveries.index') }}" class="flex items-center space-x-3 p-3 {{ Request::routeIs('deliveries.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl font-semibold transition">
                        <i class="fas fa-truck w-6"></i>
                        <span>Livraisons</span>
                    </a>
                    <a href="{{ route('reviews.index') }}" class="flex items-center space-x-3 p-3 {{ Request::routeIs('reviews.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl font-semibold transition">
                        <i class="fas fa-star w-6"></i>
                        <span>Mes Avis</span>
                    </a>
                    <hr class="border-white/5 my-4">
                    <a href="{{ route('account.settings') }}" class="flex items-center space-x-3 p-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition">
                        <i class="fas fa-cog w-6"></i>
                        <span>Paramètres</span>
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <hr class="border-white/5 my-4">
                        <div class="text-xs font-semibold text-indigo-400 uppercase px-3 py-2">👑 Admin</div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-3 {{ Request::routeIs('admin.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl transition">
                            <i class="fas fa-chart-line w-6"></i>
                            <span>Dashboard Admin</span>
                        </a>
                        <a href="{{ route('admin.boxes.index') }}" class="flex items-center space-x-3 p-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition">
                            <i class="fas fa-cubes w-6"></i>
                            <span>Gestion des Boxes</span>
                        </a>
                    @endif
                </nav>

                <div class="glass p-6 rounded-xl sticky top-8">
                    <div class="relative inline-block mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=818cf8&color=fff" class="h-16 w-16 rounded-2xl mx-auto" alt="Avatar">
                        <span class="absolute bottom-2 right-0 h-4 w-4 bg-green-500 border-4 border-[#0f172a] rounded-full"></span>
                    </div>
                    <h3 class="font-bold text-center">{{ auth()->user()->name }}</h3>
                    <p class="text-xs text-slate-500 text-center mb-4">👑 {{ ucfirst(auth()->user()->membership_tier) }} | Depuis {{ optional(auth()->user()->member_since)->format('Y') ?? now()->format('Y') }}</p>
                    <div class="space-y-2">
                        <a href="{{ route('account.settings') }}" class="block w-full py-2 text-center bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-400/20 rounded-lg text-sm transition font-semibold text-indigo-300">
                            <i class="fas fa-user-circle mr-2"></i>Profil
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full py-2 bg-red-500/10 hover:bg-red-500/20 border border-red-400/20 rounded-lg text-sm transition font-semibold text-red-300">
                                <i class="fas fa-sign-out-alt mr-2"></i>Quitter
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="col-span-9">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Main Content (only when menu hidden) -->
        <main class="lg:hidden" id="mobileContent">
            @yield('content')
        </main>
    </div>

    <script>
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileAccountCard = document.getElementById('mobileAccountCard');
        const mobileContent = document.getElementById('mobileContent');

        mobileMenuToggle.addEventListener('click', function() {
            const isHidden = mobileMenu.classList.contains('hidden');
            
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                mobileAccountCard.classList.remove('hidden');
                mobileContent.classList.add('hidden');
            } else {
                mobileMenu.classList.add('hidden');
                mobileAccountCard.classList.add('hidden');
                mobileContent.classList.remove('hidden');
            }
        });

        // Close menu when clicking on a link
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                mobileAccountCard.classList.add('hidden');
                mobileContent.classList.remove('hidden');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
