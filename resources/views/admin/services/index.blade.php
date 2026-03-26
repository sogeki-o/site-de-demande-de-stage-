@extends('layouts.admin')

@section('title', 'Gestion des services')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Services UCA</h1>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau service
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Responsable</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Utilisateurs</th>
                            <th>Demandes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>#{{ $service->id }}</td>
                                <td>{{ $service->nom }}</td>
                                <td>{{ $service->responsable_nom ?? '-' }}</td>
                                <td>{{ $service->responsable_email ?? '-' }}</td>
                                <td>
                                    @if ($service->actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>{{ $service->users_count ?? 0 }}</td>
                                <td>{{ $service->demandes_count ?? 0 }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.services.show', $service) }}"
                                            class="btn btn-outline-primary" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                            class="btn btn-outline-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Aucun service trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($services->hasPages())
                <div class="mt-3">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
