@extends('layouts.app')

@section('title', 'Créer une demande de stage')

@section('content')
    <div class="container">
        <h1 class="mb-4">Nouvelle demande de stage</h1>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <strong>Informations du compte</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->nom }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Prenom</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->prenom }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->email }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('demandeur.demandes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="niveau_etude" class="form-label">Niveau d'étude</label>
                                <input type="text" class="form-control @error('niveau_etude') is-invalid @enderror"
                                    id="niveau_etude" name="niveau_etude" value="{{ old('niveau_etude') }}" required>
                                @error('niveau_etude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="etablissement" class="form-label">Établissement</label>
                                <input type="text" class="form-control @error('etablissement') is-invalid @enderror"
                                    id="etablissement" name="etablissement" value="{{ old('etablissement') }}" required>
                                @error('etablissement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="filiere" class="form-label">Filière</label>
                                <input type="text" class="form-control @error('filiere') is-invalid @enderror"
                                    id="filiere" name="filiere" value="{{ old('filiere') }}" required>
                                @error('filiere')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="duree_stage" class="form-label">Durée du stage(mois)</label>
                                        <input type="number"
                                            class="form-control @error('duree_stage') is-invalid @enderror" id="duree_stage"
                                            name="duree_stage" value="{{ old('duree_stage') }}" min="1"
                                            max="12"required>
                                        @error('duree_stage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_debut_prevue" class="form-label">Date début prévue</label>
                                        <input type="date"
                                            class="form-control @error('date_debut_prevue') is-invalid @enderror"
                                            id="date_debut_prevue" name="date_debut_prevue"
                                            value="{{ old('date_debut_prevue') }}" required>
                                        @error('date_debut_prevue')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="service_uca_id" class="form-label">Service UCA</label>
                                <select class="form-control @error('service_uca_id') is-invalid @enderror"
                                    id="service_uca_id" name="service_uca_id" required>
                                    <option value="">Sélectionner un service</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}"
                                            {{ old('service_uca_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_uca_id')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cv_path" class="form-label">CV
                                    ({{ strtoupper($allowedCvMimes ?? 'PDF,DOC,DOCX') }} - Max 10 Mo)</label>
                                <input type="file" class="form-control @error('cv_path') is-invalid @enderror"
                                    id="cv_path" name="cv_path" accept="{{ $allowedCvAccept ?? '.pdf,.doc,.docx' }}"
                                    required>
                                <div class="form-text">Formats acceptes: {{ $allowedCvMimes ?? 'pdf,doc,docx' }}</div>
                                @error('cv_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Soumettre la demande</button>
                                <a href="{{ route('demandeur.demandes.index') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
