@extends('layouts.admin')

@section('title', 'Modifier document requis')

@section('content')
    <div class="container">
        <h1 class="h3 mb-4">Modifier document requis</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.required-documents.update', $requiredDocument) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nom du document</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $requiredDocument->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $requiredDocument->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" min="0" name="sort_order"
                            class="form-control @error('sort_order') is-invalid @enderror"
                            value="{{ old('sort_order', $requiredDocument->sort_order) }}">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $requiredDocument->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Document actif</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre a jour</button>
                    <a href="{{ route('admin.required-documents.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
@endsection
