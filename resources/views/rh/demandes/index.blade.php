@extends('layouts.app')

@section('title', 'Liste des demandes - RH')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Demandes de stage - RH</h1>
            <a href="{{ route('rh.export', request()->query()) }}" class="btn btn-outline-success">
                <i class="fas fa-download me-2"></i>Exporter
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm h-100">
                    <div class="card-body"><small>Total</small>
                        <h4 class="mb-0">{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white shadow-sm h-100">
                    <div class="card-body"><small>En attente</small>
                        <h4 class="mb-0">{{ $stats['en_attente'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body"><small>Acceptées</small>
                        <h4 class="mb-0">{{ $stats['acceptees'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow-sm h-100">
                    <div class="card-body"><small>Refusées</small>
                        <h4 class="mb-0">{{ $stats['refusees'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('rh.demandes') }}" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Nom, email, établissement...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous</option>
                            @foreach (['soumise', 'refusee_rh', 'acceptee_rh', 'affectee_service', 'entretien_planifie', 'entretien_realise', 'sujet_renseigne', 'cahier_charges_partage', 'cloturee'] as $statut)
                                <option value="{{ $statut }}" {{ request('statut') === $statut ? 'selected' : '' }}>
                                    {{ $statut }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Service</label>
                        <select name="service_uca_id" class="form-select">
                            <option value="">Tous</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"
                                    {{ (string) request('service_uca_id') === (string) $service->id ? 'selected' : '' }}>
                                    {{ $service->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Période</label>
                        <select name="periode" class="form-select">
                            <option value="all" {{ request('periode', 'all') === 'all' ? 'selected' : '' }}>Toutes
                            </option>
                            <option value="today" {{ request('periode') === 'today' ? 'selected' : '' }}>Aujourd'hui
                            </option>
                            <option value="week" {{ request('periode') === 'week' ? 'selected' : '' }}>Semaine</option>
                            <option value="month" {{ request('periode') === 'month' ? 'selected' : '' }}>Mois</option>
                            <option value="year" {{ request('periode') === 'year' ? 'selected' : '' }}>Année</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tri</label>
                        <select name="sort_by" class="form-select mb-2">
                            <option value="created_at"
                                {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Date création
                            </option>
                            <option value="date_soumission"
                                {{ request('sort_by') === 'date_soumission' ? 'selected' : '' }}>Date soumission</option>
                            <option value="statut" {{ request('sort_by') === 'statut' ? 'selected' : '' }}>Statut</option>
                        </select>
                        <select name="sort_dir" class="form-select">
                            <option value="desc" {{ request('sort_dir', 'desc') === 'desc' ? 'selected' : '' }}>
                                Décroissant</option>
                            <option value="asc" {{ request('sort_dir') === 'asc' ? 'selected' : '' }}>Croissant</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                        <a href="{{ route('rh.demandes') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Demandeur</th>
                            <th>Service</th>
                            <th>Statut</th>
                            <th>Soumission</th>
                            <th>Traité par RH</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $demande)
                            <tr>
                                <td>{{ $demande->id }}</td>
                                <td>
                                    <div>{{ $demande->user->prenom }} {{ $demande->user->nom }}</div>
                                    <small class="text-muted">{{ $demande->user->email }}</small>
                                </td>
                                <td>{{ $demande->service->nom ?? '-' }}</td>
                                <td>@include('partials.statut-badge', ['statut' => $demande->statut])</td>
                                <td>{{ optional($demande->date_soumission)->format('d/m/Y H:i') }}</td>
                                <td>{{ $demande->traitePar?->prenom ?? '-' }} {{ $demande->traitePar?->nom ?? '' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('rh.demandes.show', $demande) }}"
                                        class="btn btn-sm btn-outline-primary">Voir détail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucune demande trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $demandes->links() }}
            </div>
        </div>
    </div>
@endsection
