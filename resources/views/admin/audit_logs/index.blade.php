@extends('layouts.admin')

@section('title', 'Journalisation des actions critiques')

@section('content')
    <div class="container">
        <h1 class="h3 mb-4">Journal des actions critiques</h1>

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Action</label>
                        <input type="text" name="action" value="{{ request('action') }}" class="form-control"
                            placeholder="ex: admin.user.update">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type entite</label>
                        <input type="text" name="entity_type" value="{{ request('entity_type') }}" class="form-control"
                            placeholder="ex: User">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filtrer</button>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Entite</th>
                            <th>Description</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $log->user?->prenom ?? '-' }} {{ $log->user?->nom ?? '' }}</td>
                                <td><code>{{ $log->action }}</code></td>
                                <td>{{ $log->entity_type ?? '-' }}{{ $log->entity_id ? ' #' . $log->entity_id : '' }}</td>
                                <td>{{ $log->description ?? '-' }}</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucune action journalisee.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $logs->links() }}</div>
        </div>
    </div>
@endsection
