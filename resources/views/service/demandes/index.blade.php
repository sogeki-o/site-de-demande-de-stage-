@extends('layouts.app')

@section('title', 'Demandes de stage - Service')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-file-alt me-2"></i>Demandes de stage</h1>
        </div>

        <!-- Filtres rapides -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('service.demandes') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="statut" class="form-label">Statut</label>
                        <select name="statut" id="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="affectee_service"
                                {{ request('statut') == 'affectee_service' ? 'selected' : '' }}>Nouvelles (affectées)
                            </option>
                            <option value="entretien_planifie"
                                {{ request('statut') == 'entretien_planifie' ? 'selected' : '' }}>Entretien planifié
                            </option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours
                                (toutes les demandes traitees)</option>
                            <option value="entretien_realise"
                                {{ request('statut') == 'entretien_realise' ? 'selected' : '' }}>Entretien réalisé</option>
                            <option value="sujet_renseigne" {{ request('statut') == 'sujet_renseigne' ? 'selected' : '' }}>
                                Sujet renseigné</option>
                            <option value="cahier_charges_partage"
                                {{ request('statut') == 'cahier_charges_partage' ? 'selected' : '' }}>Cahier des charges
                                disponible</option>
                            <option value="refusee_service" {{ request('statut') == 'refusee_service' ? 'selected' : '' }}>
                                Refusée</option>
                            <option value="cloturee" {{ request('statut') == 'cloturee' ? 'selected' : '' }}>Clôturée
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Nom, prénom, établissement..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('service.demandes') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Demandeur</th>
                                <th>Niveau</th>
                                <th>Établissement</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($demandes as $demande)
                                <tr>
                                    <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $demande->user->prenom }} {{ $demande->user->nom }}</td>
                                    <td>{{ $demande->niveau_etude }}</td>
                                    <td>{{ $demande->etablissement }}</td>
                                    <td>@include('partials.statut-badge', ['statut' => $demande->statut])</td>
                                    <td>
                                        <a href="{{ route('service.demandes.show', $demande) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Aucune demande trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $demandes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
