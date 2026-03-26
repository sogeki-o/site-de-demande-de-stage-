@extends('layouts.app')

@section('title', 'Mes demandes')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Mes demandes</h1>
            <a href="{{ route('demandeur.demandes.create') }}" class="btn btn-primary">Nouvelle demande</a>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Niveau</th>
                            <th>Statut</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $demande)
                            <tr>
                                <td>{{ $demande->id }}</td>
                                <td>{{ optional($demande->date_soumission ?? $demande->created_at)->format('d/m/Y') }}</td>
                                <td>{{ $demande->service?->nom_service ?? ($demande->service?->nom ?? '-') }}</td>
                                <td>{{ $demande->niveau_etude }}</td>
                                <td>@include('partials.statut-badge', ['statut' => $demande->statut])</td>
                                <td class="text-end">
                                    <a href="{{ route('demandeur.demandes.show', $demande) }}"
                                        class="btn btn-sm btn-outline-primary">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucune demande pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (method_exists($demandes, 'links'))
                <div class="card-footer bg-white">
                    {{ $demandes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
