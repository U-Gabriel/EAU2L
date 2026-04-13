<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #020617; color: #ffffff; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #020617; padding-bottom: 40px; }
        .main { background-color: #0d0d0f; margin: 0 auto; width: 100%; max-width: 600px; border: 1px solid #1e293b; border-radius: 8px; overflow: hidden; margin-top: 40px; }
        .header { padding: 40px; text-align: center; border-bottom: 4px solid #3b82f6; }
        .content { padding: 40px; line-height: 1.6; color: rgba(255,255,255,0.8); }
        .title { color: #ffffff; font-size: 24px; font-weight: 900; text-transform: uppercase; letter-spacing: -1px; margin-bottom: 20px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: rgba(255,255,255,0.3); }
        .btn { display: inline-block; padding: 12px 24px; background-color: #3b82f6; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
        img { max-width: 100%; height: auto; border-radius: 4px; margin-bottom: 20px; }

        .btn { 
            display: inline-block; 
            padding: 15px 35px; 
            background-color: #3b82f6; 
            color: #ffffff !important; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: 800; 
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <h1 style="color:white; margin:0; font-size: 20px; letter-spacing: 2px;">ARMATURE <span style="color:#3b82f6;">BUSINESS</span></h1>
            </div>
            <div class="content">
                @if($image)
                    <img src="{{ $message->embed(public_path($image)) }}" alt="{{ $title }}">
                @endif
                <h2 class="title">{{ $title }}</h2>
                <div>{!! $description !!}</div>
                <center style="margin-top: 30px; margin-bottom: 10px;">
                    <a href="{{ url('/insights') }}" class="btn">
                        LIRE L'ANALYSE COMPLÈTE
                    </a>
                </center>

                <p style="text-align: center; margin-top: 15px;">
                    <a href="{{ url('/insights') }}" style="color: rgba(255,255,255,0.4); font-size: 11px; text-decoration: underline;">
                        Voir toutes nos actualités stratégiques
                    </a>
                </p>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} Armature Business. Note d'expertise stratégique.<br>
                Vous recevez ce mail car vous êtes inscrit à notre veille stratégique.
            </div>
        </div>
    </div>
</body>
</html>