<x-guest-layout>
    <style>
        /* 1. Harmonisation avec le thème Elite */
        .min-h-screen {
            background-color: #0a0a0c !important; /* --bg-dark */
            background-image: radial-gradient(circle at 100% 100%, rgba(59, 130, 246, 0.05) 0%, transparent 50%) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 40px 20px !important;
        }

        /* Masquer le logo Laravel par défaut */
        div.min-h-screen > div:first-child a { 
            display: none !important; 
        }

        /* 2. Style de la Carte */
        .register-card {
            background: #131316; /* --card-bg */
            border: 1px solid #27272a; /* --border-color */
            border-radius: 24px;
            padding: 45px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .register-card h2 {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -1px;
            background: linear-gradient(to bottom right, #fff 50%, #71717a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 8px;
        }

        .register-card p.subtitle {
            color: #a1a1aa;
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 35px;
        }

        /* 3. Style des Champs */
        .field-group {
            margin-bottom: 20px;
        }

        .custom-label {
            color: #f4f4f5 !important;
            font-weight: 500 !important;
            font-size: 0.85rem !important;
            margin-bottom: 8px !important;
            display: block;
        }

        .custom-input {
            background-color: #0a0a0c !important;
            border: 1px solid #27272a !important;
            color: #f4f4f5 !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            width: 100%;
            transition: all 0.2s ease;
        }

        .custom-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 1px #3b82f6 !important;
            outline: none;
        }

        /* 4. Bouton Principal */
        .btn-register-elite {
            background-color: #f4f4f5 !important;
            color: #0a0a0c !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 14px !important;
            font-weight: 700 !important;
            width: 100%;
            margin-top: 15px;
            transition: all 0.2s ease !important;
            cursor: pointer;
        }

        .btn-register-elite:hover {
            background-color: #e4e4e7 !important;
            transform: translateY(-2px);
        }

        .login-link {
            color: #a1a1aa !important;
            font-size: 0.85rem;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link:hover {
            color: #3b82f6 !important;
        }
    </style>

    <div class="register-card">
        <div class="mb-4 text-center">
            <h2>Créer un compte</h2>
            <p class="subtitle">Rejoignez la plateforme AnalyseProjets</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field-group">
                <x-input-label for="name" :value="__('Nom complet')" class="custom-label" />
                <x-text-input id="name" class="custom-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ex: Jean Dupont" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="field-group">
                <x-input-label for="email" :value="__('Adresse Email')" class="custom-label" />
                <x-text-input id="email" class="custom-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="jean@exemple.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="field-group">
                <x-input-label for="password" :value="__('Mot de passe')" class="custom-label" />
                <x-text-input id="password" class="custom-input"
                                type="password"
                                name="password"
                                required autocomplete="new-password" 
                                placeholder="Minimum 8 caractères" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="field-group">
                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="custom-label" />
                <x-text-input id="password_confirmation" class="custom-input"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" 
                                placeholder="Répétez votre mot de passe" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-8">
                <button type="submit" class="btn-register-elite">
                    {{ __('Créer mon compte') }}
                </button>
            </div>

            <div class="mt-6 text-center border-t border-gray-800 pt-6">
                <p class="text-sm text-gray-500">
                    Déjà inscrit ? 
                    <a href="{{ route('login') }}" class="login-link text-blue-500 ml-1">
                        Se connecter
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>