<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AnalyseProjets | Plateforme d'Elite</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-dark: #0a0a0c;
            --card-bg: #131316;
            --primary-accent: #3b82f6; /* Bleu électrique */
            --secondary-accent: #10b981; /* Vert succès */
            --text-main: #f4f4f5;
            --text-muted: #a1a1aa;
            --border-color: #27272a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Effet de lumière diffuse en arrière-plan */
        .glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, rgba(10, 10, 12, 0) 70%);
            top: -100px;
            right: -100px;
            z-index: 0;
        }

        /* Navbar épurée */
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--text-main) !important;
            font-size: 1.5rem;
        }

        .navbar-brand span {
            color: var(--primary-accent);
        }

        /* Hero Container */
        .hero-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            z-index: 1;
        }

        .main-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 60px;
            max-width: 900px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .badge-pfe {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-accent);
            border: 1px solid rgba(59, 130, 246, 0.2);
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 24px;
            display: inline-block;
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            background: linear-gradient(to bottom right, #fff 50%, #71717a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p.description {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 40px;
            line-height: 1.6;
        }

        /* Boutons Professionnels */
        .btn-group-custom {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .btn-main {
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary-custom {
            background-color: var(--text-main);
            color: var(--bg-dark);
        }

        .btn-primary-custom:hover {
            background-color: #e4e4e7;
            transform: translateY(-2px);
        }

        .btn-secondary-custom {
            background-color: transparent;
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .btn-secondary-custom:hover {
            background-color: rgba(255,255,255,0.05);
            border-color: #52525b;
        }

        /* Grid des Features (Preuve de concept) */
        .feature-grid {
            margin-top: 60px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            border-top: 1px solid var(--border-color);
            padding-top: 40px;
        }

        .feature-item {
            text-align: left;
        }

        .feature-item i {
            color: var(--primary-accent);
            font-size: 1.5rem;
            margin-bottom: 12px;
            display: block;
        }

        .feature-item h6 {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .feature-item p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0;
        }

        footer {
            padding: 30px;
            text-align: center;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            h1 { font-size: 2.5rem; }
            .feature-grid { grid-template-columns: 1fr; }
            .main-card { padding: 30px; }
            .btn-group-custom { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="glow"></div>

    <nav class="navbar px-4 pt-4">
        <a class="navbar-brand" href="#">
            <span>Analyse</span>Projets.
        </a>
    </nav>

    <div class="hero-container">
        <div class="main-card">
            <span class="badge-pfe">Intelligence Artificielle & Qualité Logicielle</span>
            
            <h1>Analysez votre code <br> avec précision.</h1>
            
            <p class="description">
                La plateforme de contrôle qualité automatisée pour les développeurs exigeants. 
                Détectez les failles, optimisez la logique et certifiez vos projets.
            </p>

            <div class="btn-group-custom">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-main btn-primary-custom">
                        Lancer une analyse
                    </a>
                @endif

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-main btn-secondary-custom">
                        Créer un compte
                    </a>
                @endif
            </div>

            <div class="feature-grid">
                <div class="feature-item">
                    <i class="bi bi-cpu"></i>
                    <h6>Module IA</h6>
                    <p>Classification automatique des erreurs via Python.</p>
                </div>
                <div class="feature-item">
                    <i class="bi bi-github"></i>
                    <h6>Intégration</h6>
                    <p>Importation directe depuis vos dépôts GitHub.</p>
                </div>
                <div class="feature-item">
                    <i class="bi bi-bar-chart"></i>
                    <h6>Dashboard</h6>
                    <p>Score de qualité et rapports PDF détaillés.</p>
                </div>
            </div>
        </div>
    </div>

    <footer>
        Projet de Fin d'Études — EST Salé — Réalisé par <b>Missan El Amrani</b> & <b>Aya Bihi</b>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>