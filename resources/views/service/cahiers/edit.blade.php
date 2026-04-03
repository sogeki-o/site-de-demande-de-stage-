@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Modifier le cahier de charge</h2>
        <form action="{{ route('service.cahiers.update', ['cahier' => $cahier->id]) }}" method="POST"
            enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="sujet_stage" class="form-label">Sujet du stage</label>
                <input type="text" name="sujet_stage" id="sujet_stage" class="form-control"
                    value="{{ old('sujet_stage', $cahier->sujet_stage) }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $cahier->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="fichier_path" class="form-label">Fichier cahier des charges (laisser vide pour ne pas
                    changer)</label>
                <input type="file" name="fichier_path" id="fichier_path" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('service.cahiers.show', ['cahier' => $cahier->id]) }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
