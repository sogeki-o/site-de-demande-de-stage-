<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion des Stages - UCA')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .statut-badge {
            font-size: 0.85rem;
        }
        
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    
    </style>
</head>

<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('accueil') }}">
                UCA Stages
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @auth
                        @php $user = Auth::user(); @endphp

                        @if ($user->role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.services.index') }}">Services</a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('admin.utilisateurs.index') }}">Utilisateurs</a></li>
                        @elseif($user->role === 'rh')
                            <li class="nav-item"><a class="nav-link" href="{{ route('rh.dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('rh.demandes') }}">Demandes</a></li>
                        @elseif($user->role === 'service')
                            <li class="nav-item"><a class="nav-link" href="{{ route('service.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('service.dashboard') }}">Demandes</a>
                            </li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('demandeur.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('demandeur.demandes.index') }}">Mes
                                    demandes</a></li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Mon profil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-0" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Contenu Principal -->
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-3">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Université Cadi Ayyad - Gestion des stages. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
