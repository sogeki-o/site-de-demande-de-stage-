@extends('layouts.admin')

@section('title', 'Parametrage des modeles d\'emails')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Modeles d'emails</h1>
            <a href="{{ route('admin.email-templates.create') }}" class="btn btn-primary">Nouveau modele</a>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Sujet</th>
                            <th>Actif</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td><code>{{ $template->code }}</code></td>
                                <td>{{ $template->name }}</td>
                                <td>{{ $template->subject }}</td>
                                <td>{!! $template->is_active
                                    ? '<span class="badge bg-success">Oui</span>'
                                    : '<span class="badge bg-secondary">Non</span>' !!}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.email-templates.edit', $template) }}"
                                        class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="{{ route('admin.email-templates.destroy', $template) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Supprimer ce modele ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun modele.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $templates->links() }}</div>
        </div>
    </div>
@endsection
