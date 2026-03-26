<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès à votre tableau de bord</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Bienvenue sur la plateforme des stages</h1>
        <p>Bonjour {{ $user->prenom }} {{ $user->nom }},</p>

        @if (!empty($verifyUrl))
            <p>Votre inscription a bien ete enregistree.</p>
            <p>Pour activer votre compte, veuillez verifier votre adresse email en cliquant sur le bouton ci-dessous :
            </p>
            <a href="{{ $verifyUrl }}" class="button">Verifier mon adresse email</a>
            <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
            <p>{{ $verifyUrl }}</p>
        @else
            <p>Votre inscription a bien ete enregistree.</p>
            <p>Vous pouvez desormais consulter votre tableau de bord et suivre les informations qui vous sont destinees
                en cliquant sur le bouton ci-dessous :</p>
            <a href="{{ $dashboardUrl }}" class="button">Acceder a mon espace demandeur</a>
            <p>Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :</p>
            <p>{{ $dashboardUrl }}</p>
        @endif

        <p>Nous vous remercions de votre collaboration.</p>
        <p>Cordialement,<br>L'equipe de gestion des stages</p>
    </div>
</body>

</html>
