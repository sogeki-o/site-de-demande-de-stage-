@extends('layouts.app')

@section('title', 'Tableau de bord - Demandeur')

@section('content')
    <div class="container">
        <!-- En-tête -->
        <div class="p-4 mb-4 bg-primary text-white rounded-3 shadow">
            <h1 class="h3 mb-0"><i class="fas fa-tachometer-alt me-2"></i>Tableau de bord - Demandeur</h1>
            <p class="mb-0 opacity-75">Bonjour {{ Auth::user()->prenom }} {{ Auth::user()->nom }}, bienvenue sur votre espace
                personnel.</p>
        </div>

        <!-- Statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total demandes</h6>
                                <h3 class="mb-0">{{ $demandes->count() }}</h3>
                            </div>
                            <i class="fas fa-file-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">En attente</h6>
                                <h3 class="mb-0 text-warning">{{ $demandes->where('statut', 'soumise')->count() }}</h3>
                            </div>
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Acceptées</h6>
                                <h3 class="mb-0 text-success">
                                    {{ $demandes->whereIn('statut', ['acceptee_rh', 'affectee_service', 'prise_en_charge_acceptee'])->count() }}
                                </h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Notifications</h6>
                                <h3 class="mb-0 text-purple">{{ $notificationsNonLues ?? 0 }}</h3>
                            </div>
                            <i class="fas fa-bell fa-2x text-purple"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-plus-circle text-success me-2"></i>Nouvelle demande</h5>
                        <p class="card-text text-muted">Soumettez une nouvelle demande de stage</p>
                        <a href="{{ route('demandeur.demandes.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Créer une demande
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-bell text-warning me-2"></i>Dernières notifications</h5>
                        @if (isset($notifications) && $notifications->count() > 0)
                            <ul class="list-unstyled">
                                @foreach ($notifications->take(3) as $notif)
                                    <li class="border-bottom py-2 small">{{ $notif->message }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">Aucune notification</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières demandes -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history text-primary me-2"></i>Dernières demandes</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes->take(5) as $demande)
                            <tr>
                                <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                                <td>{{ $demande->service->nom }}</td>
                                <td>@include('partials.statut-badge', ['statut' => $demande->statut])</td>
                                <td>
                                    <a href="{{ route('demandeur.demandes.show', $demande) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Aucune demande trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('demandeur.demandes.index') }}" class="text-decoration-none">
                    Voir toutes mes demandes <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <style>
        .text-purple {
            color: #6f42c1;

            /* Pour que le footer reste en bas */
            html,
            body {
                height: 100%;
                margin: 0;
            }

            body {
                display: flex;
                flex-direction: column;
            }

            main {
                flex: 1 0 auto;
                /* le contenu principal prend tout l'espace disponible */

            }

            footer {
                flex-shrink: 0;
                /* le footer ne se réduit pas */
            }
        }
    </style>
@endsection
