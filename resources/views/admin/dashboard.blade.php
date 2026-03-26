@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container">
        <div class="p-4 mb-4 bg-primary text-white rounded-3 shadow-sm">
            <h1 class="h3 mb-1">Tableau de bord Administrateur</h1>
            <p class="mb-0 opacity-75">Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }}.</p>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Utilisateurs</p>
                        <h3 class="mb-0">{{ $stats['users_total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Services</p>
                        <h3 class="mb-0">{{ $stats['services_total'] }}</h3>
                        <small class="text-success">{{ $stats['services_actifs'] }} actifs</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Demandes</p>
                        <h3 class="mb-0">{{ $stats['demandes_total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">En attente</p>
                        <h3 class="mb-0 text-warning">{{ $stats['demandes_soumises'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Etat des demandes</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between py-1"><span>Soumises</span><strong
                                class="text-warning">{{ $stats['demandes_soumises'] }}</strong></div>
                        <div class="d-flex justify-content-between py-1"><span>Acceptées</span><strong
                                class="text-success">{{ $stats['demandes_acceptees'] }}</strong></div>
                        <div class="d-flex justify-content-between py-1"><span>Refusées</span><strong
                                class="text-danger">{{ $stats['demandes_refusees'] }}</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Administration et referentiels</h5>
                    </div>
                    <div class="card-body d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary">Liste des
                            services</a>
                        <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline-primary">Utilisateurs et
                            habilitations</a>
                        <a href="{{ route('admin.required-documents.index') }}" class="btn btn-outline-primary">Documents a
                            deposer</a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-dark">Journal des actions
                            critiques</a>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Gestion comptes</h5>
                        <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-sm btn-primary">Gerer les
                            comptes</a>
                    </div>
                    <div class="card-body d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-outline-success">Creer un
                            compte</a>
                        <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline-secondary">Voir tous les
                            comptes</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Derniers utilisateurs</h5>
                        <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-sm btn-outline-primary">Voir
                            tout</a>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($recentUsers as $user)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $user->prenom }} {{ $user->nom }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <span class="badge bg-secondary text-uppercase">{{ $user->role }}</span>
                            </div>
                        @empty
                            <div class="list-group-item text-muted">Aucun utilisateur trouvé.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dernières demandes</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($recentDemandes as $demande)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-semibold">{{ $demande->user->prenom ?? '' }}
                                        {{ $demande->user->nom ?? '' }}</div>
                                    <small class="text-muted">{{ $demande->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="small text-muted">{{ $demande->service->nom ?? 'Service non défini' }}</div>
                                <div class="mt-1">@include('partials.statut-badge', ['statut' => $demande->statut])</div>
                            </div>
                        @empty
                            <div class="list-group-item text-muted">Aucune demande trouvée.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
