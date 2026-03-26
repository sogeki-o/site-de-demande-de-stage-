@extends('layouts.admin')

@section('title', 'Nouveau modele d\'email')

@section('content')
    <div class="container">
        <h1 class="h3 mb-4">Nouveau modele d'email</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.email-templates.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Code unique</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet</label>
                        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                            value="{{ old('subject') }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Corps du modele</label>
                        <textarea name="body" rows="8" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            checked>
                        <label class="form-check-label" for="is_active">Modele actif</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.email-templates.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
@endsection
