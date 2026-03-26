<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convocation a un entretien</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background-color: #f9f9f9;
            border-radius: 6px;
            padding: 20px;
        }

        .meta {
            margin: 10px 0;
            padding: 12px;
            background-color: #fff;
            border-radius: 6px;
        }

        .label {
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Convocation a l'entretien de stage</h1>
        <p>Bonjour {{ $demande->user->prenom }} {{ $demande->user->nom }},</p>
        <p>Votre demande de stage a ete prise en charge par le service
            <strong>{{ $demande->service->nom_service ?? ($demande->service->nom ?? '-') }}</strong>.
        </p>

        <div class="meta">
            <p><span class="label">Date et heure:</span> {{ optional($entretien->date_heure)->format('d/m/Y H:i') }}</p>
            <p><span class="label">Lieu:</span> {{ $entretien->lieu ?? 'Non precise' }}</p>
            <p><span class="label">Lien de reunion:</span> {{ $entretien->lien_reunion ?? 'Non precise' }}</p>
            <p><span class="label">Documents a deposer:</span>
                {{ $entretien->documents_demande ?? 'Aucun document specifie' }}</p>
        </div>

        <p>Merci de vous presenter a l'entretien avec les documents demandes.</p>
        <p>Cordialement,<br>Le service d'accueil des stagiaires</p>
    </div>
</body>

</html>
