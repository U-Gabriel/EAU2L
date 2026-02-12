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
        .details-label { font-weight: bold; color: #64748b; width: 100px; display: inline-block; }
        .btn-container { text-align: center; margin-top: 30px; }
        .btn-teams { background-color: #444791; color: #ffffff !important; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block; transition: background 0.3s; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
        .highlight { color: #1e3a8a; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>EAU2L</h1>
            <p>Expertise & Audit Digital</p>
        </div>

        <div class="content">
            <h2 style="color: #1e293b; margin-top: 0;">Confirmation de votre Audit</h2>
            <p>Bonjour <span class="highlight">{{ $info->prenom }} {{ $info->nom }}</span>,</p>
            <p>Nous avons le plaisir de vous confirmer votre rendez-vous pour un <strong>Audit Premium</strong>. Nos experts ont bien reçu vos informations concernant l'entreprise <span class="highlight">{{ $info->company_name }}</span>.</p>

            <div class="details-box">
                <div class="details-item">
                    <span class="details-label">Date :</span> 
                    {{ \Carbon\Carbon::parse($info->meeting_date)->format('d/m/Y') }}
                </div>
                <div class="details-item">
                    <span class="details-label">Heure :</span> 
                    {{ $info->meeting_hour }}
                </div>
                <div class="details-item">
                    <span class="details-label">Type :</span> 
                    Visioconférence Teams
                </div>
            </div>

            <p style="color: #475569; font-style: italic; border-left: 3px solid #1e3a8a; padding-left: 15px; margin: 25px 0;">
                "Afin d'optimiser la pertinence de notre échange, nous vous invitons à préparer tout document ou indicateur clé relatif à votre activité que vous jugeriez utile de nous partager."
            </p>

            <div class="btn-container">
                <a href="https://teams.live.com/meet/93469201237491?p=WBX9LmrsbYkHqLKMT6" class="btn-teams" style="color: #ffffff !important;">
                    Rejoindre la réunion Teams
                </a>
            </div>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement suite à votre réservation sur EAU2L.<br>
            &copy; 2026 EAU2L. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>