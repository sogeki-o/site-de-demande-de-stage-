@extends('layouts.app')

@section('title', 'Detail de la demande - Service')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Detail de la demande</h1>
            <a href="{{ route('service.demandes') }}" class="btn btn-outline-secondary">Retour a la liste</a>
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
                        <strong>Service:</strong>
                        <div>{{ $demande->service->nom_service ?? ($demande->service->nom ?? '-') }}</div>
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
                        <strong>Date de soumission:</strong>
                        <div>{{ optional($demande->date_soumission)->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Traitee par:</strong>
                        <div>{{ $demande->traitePar?->prenom ?? '-' }} {{ $demande->traitePar?->nom ?? '' }}</div>
                    </div>
                    <div class="col-12">
                        <strong>CV:</strong>
                        <div>
                            @if ($demande->cv_path)
                                <a href="{{ route('demandes.cv', $demande) }}" target="_blank" rel="noopener">Voir le
                                    CV</a>
                            @else
                                <span class="text-muted">Aucun CV joint</span>
                            @endif
                        </div>
                    </div>
                    @if ($demande->motif_refus)
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
                <h5 class="mb-3">Prise en charge du stage</h5>
                <div class="d-flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('service.demandes.accepter', $demande) }}">
                        @csrf
                        <button type="submit" class="btn btn-success"
                            {{ $demande->statut === 'refusee_service' ? 'disabled' : '' }}>
                            Accepter la prise en charge
                        </button>
                    </form>
                </div>
                <form method="POST" action="{{ route('service.demandes.refuser', $demande) }}" class="mt-3">
                    @csrf
                    <label for="motif_refus" class="form-label">Refuser la prise en charge</label>
                    <textarea id="motif_refus" name="motif_refus" rows="3"
                        class="form-control @error('motif_refus') is-invalid @enderror" required>{{ old('motif_refus') }}</textarea>
                    @error('motif_refus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-danger mt-2">Refuser</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h5 class="mb-3">Planification de l'entretien</h5>
                @php
                    $acceptedByService =
                        $demande->statut === 'prise_en_charge_acceptee' ||
                        ($demande->statut === 'affectee_service' &&
                            $demande->traitePar &&
                            $demande->traitePar->role === 'service');
                    $canPlanifierEntretien =
                        $acceptedByService ||
                        in_array($demande->statut, [
                            'entretien_planifie',
                            'entretien_realise',
                            'sujet_renseigne',
                            'cahier_charges_partage',
                        ]);
                @endphp
                <form method="POST" action="{{ route('service.demandes.planifier-entretien', $demande) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date et heure</label>
                            <input type="datetime-local" name="date_heure"
                                class="form-control @error('date_heure') is-invalid @enderror" required
                                {{ $canPlanifierEntretien ? '' : 'disabled' }}>
                            @error('date_heure')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lieu</label>
                            <input type="text" name="lieu" class="form-control @error('lieu') is-invalid @enderror"
                                placeholder="Ex: Salle B12" {{ $canPlanifierEntretien ? '' : 'disabled' }}>
                            @error('lieu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lien de reunion (optionnel)</label>
                            <input type="url" name="lien_reunion"
                                class="form-control @error('lien_reunion') is-invalid @enderror" placeholder="https://..."
                                {{ $canPlanifierEntretien ? '' : 'disabled' }}>
                            @error('lien_reunion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3"
                        {{ $canPlanifierEntretien ? '' : 'disabled' }}>Planifier et envoyer la convocation</button>
                </form>

                @if (!$canPlanifierEntretien)
                    <small class="text-muted d-block mt-2">Vous devez d'abord accepter la prise en charge pour
                        planifier l'entretien.</small>
                @endif

                @if ($demande->entretien)
                    <hr>
                    <p class="mb-2"><strong>Entretien actuel:</strong>
                        {{ optional($demande->entretien->date_heure)->format('d/m/Y H:i') }}
                        {{ $demande->entretien->realise ? '(realise)' : '(non realise)' }}</p>
                    @if (!$demande->entretien->realise)
                        <form method="POST" action="{{ route('service.demandes.entretien-realise', $demande) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-success">Marquer l'entretien comme
                                realise</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h5 class="mb-3">Deposer et partager le cahier des charges</h5>
                <form method="POST" action="{{ route('service.demandes.partager-cahier', $demande) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Sujet du stage</label>
                        <input type="text" name="sujet_stage"
                            class="form-control @error('sujet_stage') is-invalid @enderror" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Fichier cahier des charges</label>
                        <input type="file" name="fichier_path"
                            class="form-control @error('fichier_path') is-invalid @enderror" required>
                        @error('fichier_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-outline-success">Partager avec le demandeur</button>
                </form>

                @if ($demande->cahierCharge)
                    <hr>
                    <p class="mb-1"><strong>Cahier actuel:</strong> {{ $demande->cahierCharge->sujet_stage }}</p>
                    <a href="{{ route('service.demandes.cahier', $demande) }}" target="_blank" rel="noopener">Voir le
                        cahier des charges partage</a>
                @endif

                <hr>
                <h6 class="mb-3">Cloture de la demande</h6>
                <form method="POST" action="{{ route('service.demandes.cloturer', $demande) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark"
                        {{ $demande->statut === 'cahier_charges_partage' ? '' : 'disabled' }}>Cloturer la demande</button>
                </form>
                @if ($demande->statut !== 'cahier_charges_partage')
                    <small class="text-muted d-block mt-2">La cloture est disponible apres le partage du cahier des
                        charges.</small>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h5 class="mb-3">Historique des echanges et decisions</h5>
                <ul class="list-group list-group-flush">
                    @forelse(($history ?? collect()) as $event)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $event['label'] }}</span>
                            <small class="text-muted">{{ optional($event['date'])->format('d/m/Y H:i') }}</small>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Aucun historique disponible.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
