@extends('layouts.app')

@section('title', 'Tableau de bord - Service')

@section('content')
    <div class="container">

        <!-- En-tête -->
        <div class="p-4 mb-4 bg-primary text-white rounded-3 shadow d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0"><i class="fas fa-tachometer-alt me-2"></i>Tableau de bord - Service</h1>
                <p class="mb-0 opacity-75">Gestion des demandes de stage affectées à votre service</p>
            </div>
            <a href="{{ route('service.cahiers.index') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-folder-open me-1"></i> Gérer les cahiers de charge
            </a>
        </div>

        <!-- Statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total demandes</h6>
                                <h2 class="mt-2 mb-0">{{ $stats['total'] ?? 0 }}</h2>
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
                                <h6 class="mb-0">Nouvelles</h6>
                                <h2 class="mt-2 mb-0">{{ $stats['nouvelles'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">En cours</h6>
                                <h2 class="mt-2 mb-0">{{ $stats['en_cours'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-spinner fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Clôturées</h6>
                                <h2 class="mt-2 mb-0">{{ $stats['cloturees'] ?? 0 }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demandes récentes -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Demandes récentes</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($demandesRecentes ?? [] as $demande)
                            <a href="{{ route('service.dashboard', ['demande' => $demande->id]) }}"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $demande->user->prenom }} {{ $demande->user->nom }}</h6>
                                    <small class="text-muted">{{ $demande->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 small">{{ $demande->niveau_etude }} - {{ $demande->etablissement }}</p>
                                <small>@include('partials.statut-badge', ['statut' => $demande->statut])</small>
                            </a>
                        @empty
                            <div class="list-group-item text-muted text-center">Aucune demande récente</div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('service.demandes') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-arrow-right me-2"></i>Voir toutes les demandes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if (isset($selectedDemande) && $selectedDemande)
            @php
                $acceptedByService =
                    $selectedDemande->statut === 'prise_en_charge_acceptee' ||
                    ($selectedDemande->statut === 'affectee_service' &&
                        $selectedDemande->traitePar &&
                        $selectedDemande->traitePar->role === 'service');

                $canPlanifier =
                    $acceptedByService ||
                    in_array($selectedDemande->statut, ['entretien_planifie', 'entretien_realise', 'sujet_renseigne']);
                $canMarquerRealise = $selectedDemande->entretien && !$selectedDemande->entretien->realise;
                $canSaisirSujet = $selectedDemande->entretien && $selectedDemande->entretien->realise;
                $canPartagerCahier = in_array($selectedDemande->statut, [
                    'entretien_realise',
                    'sujet_renseigne',
                    'cahier_charges_partage',
                ]);
                $canCloturer = $selectedDemande->statut === 'cahier_charges_partage';
            @endphp
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2 text-primary"></i>Traitement de la demande
                                sélectionnée</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ $selectedDemande->user->prenom }} {{ $selectedDemande->user->nom }}</strong>
                                <span class="text-muted">- {{ $selectedDemande->etablissement }}</span>
                                <div>@include('partials.statut-badge', ['statut' => $selectedDemande->statut])</div>
                            </div>

                            <div class="alert alert-light border mb-3">
                                <div class="fw-semibold mb-1">Parcours recommande</div>
                                <small class="text-muted d-block">1) Accepter la prise en charge</small>
                                <small class="text-muted d-block">2) Planifier puis realiser l'entretien</small>
                                <small class="text-muted d-block">3) Deposer et partager le cahier des charges</small>
                                <small class="text-muted d-block">4) Cloturer la demande</small>
                            </div>

                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <h6>Prise en charge</h6>
                                        <form method="POST"
                                            action="{{ route('service.demandes.accepter', $selectedDemande) }}"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm"
                                                {{ $selectedDemande->statut === 'refusee_service' ? 'disabled' : '' }}>
                                                Accepter
                                            </button>
                                        </form>
                                        <form method="POST"
                                            action="{{ route('service.demandes.refuser', $selectedDemande) }}"
                                            class="mt-2">
                                            @csrf
                                            <textarea name="motif_refus" rows="2" class="form-control mb-2" placeholder="Motif du refus" required></textarea>
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                {{ $selectedDemande->statut === 'refusee_service' ? 'disabled' : '' }}>
                                                Refuser
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <h6>Planifier entretien</h6>
                                        <form method="POST"
                                            action="{{ route('service.demandes.planifier-entretien', $selectedDemande) }}">
                                            @csrf
                                            <input type="datetime-local" name="date_heure" class="form-control mb-2"
                                                required {{ $canPlanifier ? '' : 'disabled' }}>
                                            <input type="text" name="lieu" class="form-control mb-2"
                                                placeholder="Lieu" {{ $canPlanifier ? '' : 'disabled' }}>
                                            <input type="url" name="lien_reunion" class="form-control mb-2"
                                                placeholder="Lien de reunion" {{ $canPlanifier ? '' : 'disabled' }}>
                                            <button type="submit" class="btn btn-primary btn-sm"
                                                {{ $canPlanifier ? '' : 'disabled' }}>Planifier + Envoyer
                                                email</button>
                                        </form>
                                        @if ($canMarquerRealise)
                                            <form method="POST"
                                                action="{{ route('service.demandes.entretien-realise', $selectedDemande) }}"
                                                class="mt-2">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-sm">Marquer
                                                    l'entretien realise</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <h6>Partager cahier des charges</h6>
                                        <form method="POST"
                                            action="{{ route('service.demandes.partager-cahier', $selectedDemande) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="text" name="sujet_stage" class="form-control mb-2"
                                                placeholder="Sujet" required {{ $canPartagerCahier ? '' : 'disabled' }}>
                                            <textarea name="description" rows="2" class="form-control mb-2" placeholder="Description"
                                                {{ $canPartagerCahier ? '' : 'disabled' }}></textarea>
                                            <input type="file" name="fichier_path" class="form-control mb-2" required
                                                {{ $canPartagerCahier ? '' : 'disabled' }}>
                                            <button type="submit" class="btn btn-outline-success btn-sm"
                                                {{ $canPartagerCahier ? '' : 'disabled' }}>Partager</button>
                                        </form>
                                        @if (!$canPartagerCahier)
                                            <small class="text-muted d-block mt-2">Le partage du cahier est disponible
                                                apres l'entretien.</small>
                                        @endif

                                        <hr>
                                        <h6>Cloturer la demande</h6>
                                        <form method="POST"
                                            action="{{ route('service.demandes.cloturer', $selectedDemande) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-dark btn-sm"
                                                {{ $canCloturer ? '' : 'disabled' }}>Cloturer</button>
                                        </form>
                                        @if (!$canCloturer)
                                            <small class="text-muted d-block mt-2">La cloture est disponible apres le
                                                partage du cahier des charges.</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6>Historique des échanges et décisions</h6>
                            <ul class="list-group list-group-flush">
                                @forelse(($history ?? collect()) as $event)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>{{ $event['label'] }}</span>
                                        <small
                                            class="text-muted">{{ optional($event['date'])->format('d/m/Y H:i') }}</small>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">Aucun historique disponible.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions rapides -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2 text-warning"></i>Actions rapides</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('service.demandes', ['statut' => 'affectee_service']) }}"
                            class="btn btn-outline-primary me-2 mb-2">
                            <i class="fas fa-eye me-2"></i>Nouvelles demandes
                        </a>
                        <a href="{{ route('service.demandes', ['statut' => 'en_cours']) }}"
                            class="btn btn-outline-warning me-2 mb-2">
                            <i class="fas fa-spinner me-2"></i>En cours
                        </a>
                        <a href="{{ route('service.demandes') }}" class="btn btn-outline-secondary me-2 mb-2">
                            <i class="fas fa-search me-2"></i>Rechercher
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
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
        }

        footer {
            flex-shrink: 0;
        }
    </style>
@endsection
