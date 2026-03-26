@php
    $classes = [
        'brouillon' => 'bg-secondary text-white',
        'soumise' => 'bg-primary text-white',
        'en_cours_traitement_rh' => 'bg-warning text-dark',
        'refusee_rh' => 'bg-danger text-white',
        'acceptee_rh' => 'bg-success text-white',
        'affectee_service' => 'bg-info text-white',
        'prise_en_charge_acceptee' => 'bg-primary text-white',
        'refusee_service' => 'bg-danger text-white',
        'entretien_planifie' => 'bg-purple text-white',
        'entretien_realise' => 'bg-teal text-white',
        'sujet_renseigne' => 'bg-cyan text-white',
        'cahier_charges_partage' => 'bg-indigo text-white',
        'cloturee' => 'bg-secondary text-white',
    ];

    $labels = [
        'brouillon' => 'Brouillon',
        'soumise' => 'Soumise',
        'en_cours_traitement_rh' => 'En cours (RH)',
        'refusee_rh' => 'Refusée (RH)',
        'acceptee_rh' => 'Acceptée (RH)',
        'affectee_service' => 'Affectée au service',
        'prise_en_charge_acceptee' => 'Prise en charge acceptée',
        'refusee_service' => 'Refusée (Service)',
        'entretien_planifie' => 'Entretien planifié',
        'entretien_realise' => 'Entretien réalisé',
        'sujet_renseigne' => 'Sujet renseigné',
        'cahier_charges_partage' => 'Cahier des charges disponible',
        'cloturee' => 'Clôturée',
    ];

    $class = $classes[$statut] ?? 'bg-secondary text-white';
    $label = $labels[$statut] ?? $statut;

    // Pour les badges larges (optionnel)
    $badgeClass = isset($large) ? 'badge fs-6 p-2' : 'badge';
@endphp

<span class="{{ $badgeClass }} {{ $class }}">
    {{ $label }}
</span>

<style>
    /* Couleurs personnalisées pour Bootstrap */
    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .bg-teal {
        background-color: #20c997 !important;
    }

    .bg-cyan {
        background-color: #0dcaf0 !important;
    }

    .bg-indigo {
        background-color: #6610f2 !important;
    }

    .text-white {
        color: #fff !important;
    }
</style>
