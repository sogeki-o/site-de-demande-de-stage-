@extends('layouts.app')

@section('title', 'Tableau de bord - RH')

@section('content')
    <div class="container">
        <!-- En-tête -->
        <div class="p-4 mb-4 bg-primary text-white rounded-3 shadow">
            <h1 class="h3 mb-0"><i class="fas fa-tachometer-alt me-2"></i>Tableau de bord - Ressources Humaines</h1>
            <p class="mb-0 opacity-75">Gestion et suivi des demande de stage</p>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('rh.dashboard') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="periode" class="form-label">Période</label>
                        <select name="periode" id="periode" class="form-select">
                            <option value="all" {{ request('periode', 'all') === 'all' ? 'selected' : '' }}>Toutes les
                                périodes</option>
                            <option value="today" {{ request('periode') === 'today' ? 'selected' : '' }}>Aujourd'hui
                            </option>
                            <option value="week" {{ request('periode') === 'week' ? 'selected' : '' }}>Cette semaine
                            </option>
                            <option value="month" {{ request('periode') === 'month' ? 'selected' : '' }}>Ce mois</option>
                            <option value="year" {{ request('periode') === 'year' ? 'selected' : '' }}>Cette année
                            </option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary me-2">Appliquer</button>
                        <a href="{{ route('rh.dashboard') }}" class="btn btn-outline-secondary me-2">Réinitialiser</a>
                        <a href="{{ route('rh.export', ['periode' => request('periode', 'all')]) }}"
                            class="btn btn-outline-success">
                            <i class="fas fa-download me-2"></i>Exporter la période
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques globales -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total demande</h6>
                                <h2 class="mt-2 mb-0">{{ $stats['total'] }}</h2>
                            </div>
                            <i class="fas fa-file-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">En attente</h6>
                                <h2 class="mt-2 mb-0">{{ $stats['en_attente'] }}</h2>
                            </div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Acceptées</h6>
                                <h2 class="mt-2 mb-0">{{ $stats['acceptees'] }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Refusées</h6>
                                <h2 class="mt-2 mb-0">{{ $stats['refusees'] }}</h2>
                            </div>
                            <i class="fas fa-times-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deux colonnes : graphique et demande récentes -->
        <div class="row g-4 mb-4">
            <!-- Répartition par service (graphique) -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Répartition par service</h5>
                    </div>
                    <div class="card-body">
                        @if ($stats['par_service']->isNotEmpty())
                            <canvas id="servicesChart" height="250"></canvas>
                        @else
                            <p class="text-muted text-center my-5">Aucune donnée disponible</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Demandes récentes -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Demandes récentes</h5>
                    </div>
                    <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                        @forelse($demandesRecentes as $demande)
                            <a href="{{ route('rh.demandes.show', $demande) }}"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $demande->user->prenom }} {{ $demande->user->nom }}</h6>
                                    <small class="text-muted">{{ $demande->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 small">{{ $demande->service->nom }} - {{ $demande->niveau_etude }}</p>
                                <small>@include('partials.statut-badge', ['statut' => $demande->statut])</small>
                            </a>
                        @empty
                            <div class="list-group-item text-muted text-center">Aucune demande récente</div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('rh.demandes') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-right me-2"></i>Voir toutes les demande
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Actions rapides</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('rh.demandes', ['statut' => 'soumise']) }}"
                            class="btn btn-outline-primary me-2 mb-2">
                            <i class="fas fa-eye me-2"></i>Demandes en attente
                        </a>
                        <a href="{{ route('rh.export', ['periode' => request('periode', 'all')]) }}"
                            class="btn btn-outline-success me-2 mb-2">
                            <i class="fas fa-download me-2"></i>Exporter les données
                        </a>
                        <a href="#" class="btn btn-outline-info me-2 mb-2">
                            <i class="fas fa-chart-bar me-2"></i>Générer un rapport
                        </a>
                        <a href="{{ route('rh.demandes') }}" class="btn btn-outline-secondary me-2 mb-2">
                            <i class="fas fa-search me-2"></i>Rechercher une demande
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
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
    </style>
@endsection

@push('scripts')
    @if ($stats['par_service']->isNotEmpty())
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('servicesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($stats['par_service']->pluck('service.nom')) !!},
                        datasets: [{
                            data: {!! json_encode($stats['par_service']->pluck('total')) !!},
                            backgroundColor: [
                                '#4299e1', '#48bb78', '#ed8936', '#9f7aea',
                                '#f56565', '#38b2ac', '#667eea', '#f687b3'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            });
        </script>
    @endif
@endpush
