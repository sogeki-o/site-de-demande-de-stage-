@extends('layouts.app')

@section('title', 'Detail de la demande - RH')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Detail de la demande</h1>
            <a href="{{ route('rh.demandes') }}" class="btn btn-outline-secondary">Retour a la liste</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Demandeur:</strong>
                        <div>{{ $demande->user->prenom }} {{ $demande->user->nom }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong>
                        <div>{{ $demande->user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Statut:</strong>
                        <div>@include('partials.statut-badge', ['statut' => $demande->statut, 'large' => true])</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Service souhaite:</strong>
                        <div>{{ $demande->service->nom ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Niveau d'etude:</strong>
                        <div>{{ $demande->niveau_etude }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Etablissement:</strong>
                        <div>{{ $demande->etablissement }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Filiere:</strong>
                        <div>{{ $demande->filiere }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Duree (mois):</strong>
                        <div>{{ $demande->duree_stage }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Date de debut prevue:</strong>
                        <div>{{ optional($demande->date_debut_prevue)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Date de soumission:</strong>
                        <div>{{ optional($demande->date_soumission)->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Date de traitement RH:</strong>
                        <div>{{ optional($demande->date_traitement_rh)->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="col-12">
                        <strong>CV:</strong>
                        <div>
                            @if ($demande->cv_path)
                                <a href="{{ route('demandes.cv', $demande) }}" target="_blank" rel="noopener">Voir
                                    le CV</a>
                            @else
                                <span class="text-muted">Aucun CV joint</span>
                            @endif
                        </div>
                    </div>
                    @if ($demande->statut === 'refusee_rh' && $demande->motif_refus)
                        <div class="col-12">
                            <strong>Motif de refus:</strong>
                            <div class="alert alert-danger mt-2 mb-0">{{ $demande->motif_refus }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Decision RH</h5>

                @if ($demande->statut === 'soumise')
                    <div class="mb-3">
                        <form method="POST" action="{{ route('rh.demandes.accepter', $demande) }}"
                            class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-8">
                                <label class="form-label">Affecter au service UCA</label>
                                <select name="service_uca_id"
                                    class="form-select @error('service_uca_id') is-invalid @enderror" required>
                                    <option value="">-- Choisir un service --</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}"
                                            {{ (string) old('service_uca_id', $demande->service_uca_id) === (string) $service->id ? 'selected' : '' }}>
                                            {{ $service->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_uca_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Liste des documents a deposer</label>
                                <textarea name="documents_demande" rows="2" class="form-control @error('documents_demande') is-invalid @enderror"
                                    placeholder="Ex: Copie CIN, releves de notes, lettre de motivation">{{ old('documents_demande', $demande->documents_demande) }}</textarea>
                                @error('documents_demande')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success w-100">Accepter et affecter</button>
                            </div>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('rh.demandes.refuser', $demande) }}">
                        @csrf
                        <div class="mb-2">
                            <label for="motif_refus" class="form-label">
                                Motif du refus {{ $motifRefusObligatoire ? '(obligatoire)' : '(facultatif)' }}
                            </label>
                            <textarea id="motif_refus" name="motif_refus" class="form-control @error('motif_refus') is-invalid @enderror"
                                rows="3" required>{{ old('motif_refus') }}</textarea>
                            @error('motif_refus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger">Refuser la demande</button>
                    </form>
                @else
                    <div class="alert alert-info mb-0">
                        Cette demande a deja ete traitee par le RH.
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Traçabilité RH</h5>
                <div class="mb-2">
                    <strong>Traité par:</strong>
                    <span>{{ $demande->traitePar?->prenom ?? '-' }} {{ $demande->traitePar?->nom ?? '' }}</span>
                </div>
                <div class="mb-3">
                    <strong>Date de traitement RH:</strong>
                    <span>{{ optional($demande->date_traitement_rh)->format('d/m/Y H:i') ?? '-' }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse(($history ?? collect()) as $event)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $event['label'] }}</span>
                            <small class="text-muted">{{ optional($event['date'])->format('d/m/Y H:i') }}</small>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Aucune trace disponible.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
