@extends('layouts.admin')

@section('title', 'Gestion des comptes')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Gestion des comptes utilisateurs</h1>
            <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary">Nouveau compte</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Service</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->prenom }} {{ $user->nom }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-secondary text-uppercase">{{ $user->role }}</span></td>
                                <td>{{ $user->service?->nom ?? '-' }}</td>
                                <td>
                                    @if ($user->actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.utilisateurs.edit', $user) }}"
                                            class="btn btn-outline-primary">Modifier</a>
                                        <form action="{{ route('admin.utilisateurs.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Supprimer ce compte ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger"
                                                {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucun compte utilisateur trouve.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="p-3">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
@endsection
