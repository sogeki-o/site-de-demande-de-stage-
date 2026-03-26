<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision RH</title>
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

        .ok {
            color: #137333;
            font-weight: 700;
        }

        .ko {
            color: #b42318;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Decision RH sur votre demande de stage</h1>

        <p>Bonjour {{ $demande->user?->prenom }} {{ $demande->user?->nom }},</p>

        @if ($decision === 'acceptee')
            <p class="ok">Votre demande a ete acceptee par RH et affectee au service.</p>
        @else
            <p class="ko">Votre demande a ete refusee par RH.</p>
        @endif

        <div class="meta">
            <p><span class="label">Service:</span>
                {{ $demande->service?->nom_service ?? ($demande->service?->nom ?? 'Non precise') }}</p>
            <p><span class="label">Statut:</span> {{ $demande->statut }}</p>

            @if ($decision !== 'acceptee')
                <p><span class="label">Motif:</span> {{ $demande->motif_refus ?? 'Aucun motif precise' }}</p>
            @endif
        </div>

        <p>Vous pouvez consulter le detail de votre demande depuis votre espace demandeur.</p>
        <p>Cordialement,<br>L'equipe RH</p>
    </div>
</body>

</html>
