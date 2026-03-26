@extends('layouts.admin')

@section('title', 'Détail du service')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Service : {{ $serviceUCA->nom }}</h1>
        <div>
            <a href="{{ route('admin.services.edit', $serviceUCA) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">ID</dt>
                        <dd class="col-sm-7">#{{ $serviceUCA->id }}</dd>

                        <dt class="col-sm-5">Nom</dt>
                        <dd class="col-sm-7">{{ $serviceUCA->nom }}</dd>

                        <dt class="col-sm-5">Responsable</dt>
                        <dd class="col-sm-7">{{ $serviceUCA->responsable_nom ?? '-' }}</dd>

                        <dt class="col-sm-5">Email</dt>
                        <dd class="col-sm-7">{{ $serviceUCA->responsable_email ?? '-' }}</dd>

                        <dt class="col-sm-5">Statut</dt>
                        <dd class="col-sm-7">
                            @if ($serviceUCA->actif)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5">Créé le</dt>
                        <dd class="col-sm-7">{{ $serviceUCA->created_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $serviceUCA->description ?? 'Aucune description' }}</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Dernières demandes associées</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>N°</th>
                                <th>Demandeur</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($serviceUCA->demandes as $demande)
                                <tr>
                                    <td>#{{ $demande->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.utilisateurs.show', $demande->user) }}"
                                            class="text-decoration-none">
                                            {{ $demande->user->prenom }} {{ $demande->user->nom }}
                                        </a>
                                    </td>
                                    <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                                    <td>@include('partials.statut-badge', ['statut' => $demande->statut])</td>
                                    <td>
                                        <a href="{{ route('admin.utilisateurs.show', $demande->user) }}"
                                            class="btn btn-sm btn-outline-primary" title="Voir l'utilisateur">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Aucune demande pour ce service.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
