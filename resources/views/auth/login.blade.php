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
        .login-container {
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

        /* Carte de connexion */
        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 48px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        /* En-tête de la carte */
        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .card-title {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .card-subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
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

        /* Remember me & Forgot password */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-checkbox {
            width: 18px !important;
            height: 18px !important;
            background-color: var(--bg-dark) !important;
            border: 1.5px solid var(--border-color) !important;
            border-radius: 6px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }

        .form-checkbox:checked {
            background-color: var(--primary-accent) !important;
            border-color: var(--primary-accent) !important;
        }

        .form-checkbox:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none !important;
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: var(--text-muted);
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            color: var(--primary-accent) !important;
            font-size: 0.875rem !important;
            text-decoration: none !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        .forgot-link:hover {
            color: #60a5fa !important;
            text-decoration: underline !important;
        }

        /* Bouton de connexion */
        .btn-login {
            background-color: var(--text-main) !important;
            color: var(--bg-dark) !important;
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

        .btn-login:hover {
            background-color: #e4e4e7 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3) !important;
        }

        .btn-login:active {
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

        /* Footer de la carte */
        .card-footer {
            text-align: center;
            margin-top: 28px;
        }

        .footer-text {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .register-link {
            color: var(--primary-accent) !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
        }

        .register-link:hover {
            color: #60a5fa !important;
            text-decoration: underline !important;
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
        }

        /* Animation de chargement (optionnel) */
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

        .login-card {
            animation: fadeIn 0.4s ease;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .login-card {
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

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        /* Fix pour les composants Laravel */
        .block {
            display: block !important;
        }

        .mt-2 {
            margin-top: 8px !important;
        }

        .mb-4 {
            margin-bottom: 16px !important;
        }
    </style>

    <div class="login-container">
        <!-- Brand Header -->
        <div class="brand-header">
            <div class="brand-logo">Analyse<span>Projets</span></div>
            <p class="brand-tagline">Intelligence Artificielle & Qualité Logicielle</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <div class="card-header">
                <h2 class="card-title">Bienvenue</h2>
                <p class="card-subtitle">Connectez-vous à votre espace d'analyse</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="session-status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Adresse email</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        placeholder="nom@exemple.com"
                    />
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input 
                        id="password" 
                        class="form-input"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••••"
                    />
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <label for="remember_me" class="checkbox-wrapper">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            class="form-checkbox" 
                            name="remember"
                        />
                        <span class="checkbox-label">Se souvenir de moi</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login">
                    Se connecter
                </button>

                <!-- Divider -->
                <div class="divider">
                    <span class="divider-text">ou</span>
                </div>

                <!-- Register Link -->
                <div class="card-footer">
                    <p class="footer-text">
                        Vous n'avez pas de compte ?
                        <a href="{{ route('register') }}" class="register-link">
                            Créer un compte gratuitement
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer Info (Optionnel) -->
        <div style="text-align: center; margin-top: 24px; color: #52525b; font-size: 0.8rem;">
            <p>© 2026 AnalyseProjets — EST Salé</p>
        </div>
    </div>
</x-guest-layout>