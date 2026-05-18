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
    <!-- Navigation -->
    <nav class="max-w-7xl mx-auto mb-12">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-box-open text-white text-xl"></i>
                </div>
                <span class="text-2xl font-bold tracking-tight">Lux<span class="gradient-text">Box</span></span>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-slate-300 hover:text-white transition">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-sm font-semibold transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-slate-300 hover:text-white transition">Connexion</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-sm font-semibold transition">
                        S'inscrire
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto mt-16 pt-12 border-t border-white/10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                <h3 class="font-bold mb-4">À Propos</h3>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    <li><a href="#" class="hover:text-white transition">Carrières</a></li>
                    <li><a href="#" class="hover:text-white transition">Presse</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-4">Support</h3>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-white transition">Aide</a></li>
                    <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-4">Légal</h3>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-white transition">Conditions</a></li>
                    <li><a href="#" class="hover:text-white transition">Confidentialité</a></li>
                    <li><a href="#" class="hover:text-white transition">Cookies</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-4">Réseaux</h3>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-white transition">Twitter</a></li>
                    <li><a href="#" class="hover:text-white transition">Instagram</a></li>
                    <li><a href="#" class="hover:text-white transition">Facebook</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center text-sm text-slate-500 pt-8 border-t border-white/5">
            <p>&copy; 2026 LynBox. Tous droits réservés.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
