<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f7f9; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background-color: #1e3a8a; padding: 40px 20px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 1px; }
        .content { padding: 30px; line-height: 1.6; }
        .details-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 20px; margin: 20px 0; }
        .details-item { margin-bottom: 10px; font-size: 15px; }
        .details-label { font-weight: bold; color: #64748b; width: 140px; display: inline-block; }
        .btn-container { text-align: center; margin-top: 30px; }
        .btn-teams { background-color: #444791; color: #ffffff !important; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
        .highlight { color: #1e3a8a; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Armature Business - ADMINISTRATION</h1>
            <p>Notification de Nouvelle Réservation</p>
        </div>

        <div class="content">
            <h2 style="color: #1e293b; margin-top: 0;">Un nouveau rendez-vous a été planifié</h2>
            <p>Bonjour,</p>
            <p>Un client vient de valider un créneau pour un <strong>Audit Premium</strong>. Voici l'ensemble des informations recueillies :</p>

            <div class="details-box">
                <div class="details-item"><span class="details-label">Client :</span> <span class="highlight">{{ $info->prenom }} {{ $info->nom }}</span></div>
                <div class="details-item"><span class="details-label">Entreprise :</span> <span class="highlight">{{ $info->company_name }}</span></div>
                <div class="details-item"><span class="details-label">Téléphone :</span> {{ $info->tel }}</div>
                <div class="details-item"><span class="details-label">Email :</span> {{ $info->email }}</div>
                <div class="details-item"><span class="details-label">Date :</span> {{ \Carbon\Carbon::parse($info->meeting_date)->format('d/m/Y') }}</div>
                <div class="details-item"><span class="details-label">Heure :</span> {{ $info->meeting_hour }}</div>
                <div class="details-item"><span class="details-label">Forme juridique :</span> {{ $info->company_type }}</div>
                <div class="details-item"><span class="details-label">Secteur :</span> {{ $info->company_activity ?? 'Non renseigné' }}</div>
                <div class="details-item"><span class="details-label">Marge théorique :</span> {{ $info->marge_theorique }}</div>
                <div class="details-item"><span class="details-label">Chiffre d'affaires :</span> {{ $info->ca }}</div>
                <div class="details-item"><span class="details-label">Salariés :</span> {{ $info->employees ?? 'Non renseigné' }}</div>
            </div>

            <p><strong>Bilan / Observations :</strong><br>{{ $info->bilan }}</p>
            <p><strong>Attentes du client :</strong><br>{{ $info->user_expectations }}</p>
            <p><strong>Objectif choisi :</strong> {{ $info->rdv_objective }}</p>

            <div class="btn-container">
                <a href="{{ $teamsUrl }}" class="btn-teams" style="color: #ffffff !important;">
                    Lien de la réunion Teams
                </a>
            </div>
        </div>

        <div class="footer">
            <p>Notification automatique du système Armature Business.<br>&copy; 2026 Armature Business. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>