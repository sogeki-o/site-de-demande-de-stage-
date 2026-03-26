@extends('layouts.admin')

@section('title', 'Parametrage des documents a deposer')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Documents a deposer</h1>
            <a href="{{ route('admin.required-documents.create') }}" class="btn btn-primary">Nouveau document</a>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Ordre</th>
                            <th>Actif</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                            <tr>
                                <td>{{ $document->name }}</td>
                                <td>{{ $document->description ?: '-' }}</td>
                                <td>{{ $document->sort_order }}</td>
                                <td>{!! $document->is_active
                                    ? '<span class="badge bg-success">Oui</span>'
                                    : '<span class="badge bg-secondary">Non</span>' !!}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.required-documents.edit', $document) }}"
                                        class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="{{ route('admin.required-documents.destroy', $document) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Supprimer ce document ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun document.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $documents->links() }}</div>
        </div>
    </div>
@endsection
