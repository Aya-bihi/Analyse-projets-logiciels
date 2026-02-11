<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        /* Variables CSS */
        :root {
            --bg-dark: #0a0a0c;
            --card-bg: #131316;
            --primary-accent: #3b82f6;
            --secondary-accent: #10b981;
            --text-main: #f4f4f5;
            --text-muted: #a1a1aa;
            --border-color: #27272a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
        }

        /* Layout principal */
        .min-h-screen {
            background-color: var(--bg-dark) !important;
            min-height: 100vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 20px !important;
            position: relative !important;
            overflow: hidden !important;
        }

        /* Effet de lumière diffuse en arrière-plan */
        .min-h-screen::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, rgba(10, 10, 12, 0) 70%);
            top: -200px;
            right: -200px;
            pointer-events: none;
            z-index: 0;
        }

        .min-h-screen::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.05) 0%, rgba(10, 10, 12, 0) 70%);
            bottom: -150px;
            left: -150px;
            pointer-events: none;
            z-index: 0;
        }

        /* Masquer le logo par défaut */
        .min-h-screen > div:first-child a {
            display: none !important;
        }

        /* Container principal */
        .forgot-password-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
        }

        /* Logo/Brand au-dessus de la carte */
        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .brand-logo span {
            color: var(--primary-accent);
        }

        .brand-tagline {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* Carte de réinitialisation */
        .forgot-password-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* En-tête de la carte */
        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }

        /* Icône */
        .icon-container {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            background: rgba(59, 130, 246, 0.1);
            border: 2px solid rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-container svg {
            width: 32px;
            height: 32px;
            color: var(--primary-accent);
        }

        .card-title {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
            color: var(--text-main);
        }

        .card-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Badge status session */
        .session-status {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--secondary-accent);
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            text-align: center;
            line-height: 1.5;
        }

        /* Champs de formulaire */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            color: var(--text-main) !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            margin-bottom: 8px !important;
            display: block !important;
            letter-spacing: 0.3px;
        }

        .form-input {
            background-color: var(--bg-dark) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 12px !important;
            padding: 14px 16px !important;
            width: 100% !important;
            font-size: 0.95rem !important;
            transition: all 0.2s ease !important;
            font-family: 'Inter', sans-serif !important;
        }

        .form-input::placeholder {
            color: #52525b !important;
        }

        .form-input:focus {
            border-color: var(--primary-accent) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none !important;
            background-color: var(--card-bg) !important;
        }

        .form-input:hover:not(:focus) {
            border-color: #3f3f46 !important;
        }

        /* Messages d'erreur */
        .error-message {
            color: #ef4444 !important;
            font-size: 0.8rem !important;
            margin-top: 6px !important;
            display: block;
        }

        /* Bouton de soumission */
        .btn-submit {
            background-color: var(--primary-accent) !important;
            color: white !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 14px 24px !important;
            font-weight: 700 !important;
            font-size: 0.95rem !important;
            width: 100% !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-transform: none !important;
            letter-spacing: 0.3px !important;
        }

        .btn-submit:hover {
            background-color: #2563eb !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3) !important;
        }

        .btn-submit:active {
            transform: translateY(0) !important;
        }

        /* Séparateur */
        .divider {
            margin: 32px 0;
            border-top: 1px solid var(--border-color);
            position: relative;
        }

        .divider-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--card-bg);
            padding: 0 16px;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Lien de retour */
        .back-link-container {
            text-align: center;
            margin-top: 28px;
        }

        .back-link {
            color: var(--text-muted) !important;
            font-size: 0.9rem !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            transition: all 0.2s ease !important;
        }

        .back-link:hover {
            color: var(--primary-accent) !important;
        }

        .back-link svg {
            width: 16px;
            height: 16px;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .forgot-password-card {
                padding: 32px 24px;
                border-radius: 20px;
            }

            .brand-header {
                margin-bottom: 24px;
            }

            .brand-logo {
                font-size: 1.75rem;
            }

            .card-title {
                font-size: 1.5rem;
            }
        }

        /* Fix pour les composants Laravel */
        .block {
            display: block !important;
        }

        .mt-1 {
            margin-top: 4px !important;
        }

        .mt-2 {
            margin-top: 8px !important;
        }

        .mt-4 {
            margin-top: 16px !important;
        }

        .mb-4 {
            margin-bottom: 16px !important;
        }

        .w-full {
            width: 100% !important;
        }
    </style>

    <div class="forgot-password-container">
        <!-- Brand Header -->
        <div class="brand-header">
            <div class="brand-logo">Analyse<span>Projets</span></div>
            <p class="brand-tagline">Intelligence Artificielle & Qualité Logicielle</p>
        </div>

        <!-- Forgot Password Card -->
        <div class="forgot-password-card">
            <div class="card-header">
                <!-- Icône de clé -->
                <div class="icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                </div>
                
                <h2 class="card-title">Mot de passe oublié ?</h2>
                <p class="card-subtitle">
                    Pas de problème. Indiquez votre adresse email et nous vous enverrons un lien de réinitialisation pour créer un nouveau mot de passe.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="session-status" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" class="form-label" />
                    <x-text-input 
                        id="email" 
                        class="form-input block mt-1 w-full" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        placeholder="nom@exemple.com"
                    />
                    <x-input-error :messages="$errors->get('email')" class="error-message mt-2" />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Envoyer le lien de réinitialisation
                </button>

                <!-- Divider -->
                <div class="divider">
                    <span class="divider-text">ou</span>
                </div>

                <!-- Back to Login Link -->
                <div class="back-link-container">
                    <a href="{{ route('login') }}" class="back-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Retour à la connexion
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer Info -->
        <div style="text-align: center; margin-top: 24px; color: #52525b; font-size: 0.8rem;">
            <p>© 2026 AnalyseProjets — EST Salé</p>
        </div>
    </div>
</x-guest-layout>