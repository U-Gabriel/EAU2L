<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .wrapper { background-color: #f8fafc; padding: 40px 10px; }
        .container { max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        
        /* Header avec rappel logo */
        .header { background-color: #ffffff; padding: 30px; text-align: center; border-bottom: 3px solid #1e3a8a; }
        .logo-img { max-height: 50px; width: auto; }
        .brand-name { color: #1e3a8a; font-size: 14px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-top: 10px; }

        /* Contenu principal */
        .content { padding: 40px 30px; text-align: center; color: #334155; }
        h2 { color: #1e293b; font-size: 24px; margin: 0 0 15px 0; font-weight: 700; }
        p { color: #64748b; font-size: 16px; line-height: 1.6; margin-bottom: 30px; }
        
        /* Zone du Code OTP */
        .code-box { background: #f1f5f9; padding: 25px; border-radius: 12px; margin: 20px 0; border: 1px dashed #1e3a8a; }
        .code { font-size: 36px; font-weight: 800; letter-spacing: 10px; color: #1e3a8a; font-family: monospace; }
        
        .timer-info { font-size: 13px; color: #94a3b8; margin-top: 20px; }
        
        /* Footer */
        .footer { background-color: #f8fafc; padding: 25px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
        .footer-text { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <img src="https://votre-domaine.com/images/logo_pc_horizontal_1772134582.png" alt="Armature Logo" class="logo-img">
                <div class="brand-name">Armature Business</div>
            </div>

            <div class="content">
                <h2>Vérification de votre email</h2>
                <p>Voici votre code de confirmation pour finaliser votre demande de rendez-vous :</p>
                
                <div class="code-box">
                    <span class="code">{{ $otp }}</span>
                </div>
                
                <p class="timer-info">
                    <strong>Note :</strong> Ce code est valable pendant 10 minutes.<br>
                    Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email.
                </p>
            </div>

            <div class="footer">
                <p class="footer-text"><strong>Armature Business</strong></p>
                <p class="footer-text">© 2026 Armature Business. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>
</html>