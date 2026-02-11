{{-- resources/views/projects/upload.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-dark">

    <!-- SIDEBAR (même que dashboard) -->
    <aside class="sidebar">
        <nav class="sidebar-nav">
            <a href="{{ route('user.dashboard') }}" class="nav-btn">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('projects.upload') }}" class="nav-btn active">
                <i class="bi bi-cloud-upload-fill"></i>
                <span>Nouveau Projet</span>
            </a>
            <button onclick="showTab('projects')" class="nav-btn">
                <i class="bi bi-folder-fill"></i>
                <span>Mes Projets</span>
            </button>
            <button onclick="showTab('analysis')" class="nav-btn">
                <i class="bi bi-cpu-fill"></i>
                <span>Analyses IA</span>
            </button>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="page-header">
            <div>
                <h2 class="page-title">Nouveau Projet</h2>
                <p class="page-subtitle">Importez votre projet pour l'analyse automatique</p>
            </div>
        </div>

        <!-- Messages d'erreur/succès -->
        @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Choix du type d'import -->
        <div class="upload-options">
            <div class="option-card" id="zipOption" onclick="selectOption('zip')">
                <div class="option-icon">
                    <i class="bi bi-file-zip-fill"></i>
                </div>
                <h3 class="option-title">Fichier ZIP</h3>
                <p class="option-description">Téléversez votre projet sous format ZIP</p>
                <div class="option-badge">Recommandé</div>
            </div>

            <div class="option-card" id="githubOption" onclick="selectOption('github')">
                <div class="option-icon">
                    <i class="bi bi-github"></i>
                </div>
                <h3 class="option-title">Dépôt GitHub</h3>
                <p class="option-description">Importez depuis un repository GitHub</p>
                <div class="option-badge option-badge-github">Public uniquement</div>
            </div>
        </div>

        <!-- Formulaire ZIP -->
        <div id="zipForm" class="upload-form-container active">
            <div class="card-modern">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            <i class="bi bi-file-zip"></i>
                            Importer un fichier ZIP
                        </h3>
                        <p class="card-subtitle">Formats acceptés : .zip (max 50 MB)</p>
                    </div>
                </div>
                
                <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                    @csrf
                    <input type="hidden" name="type_depot" value="ZIP">

                    <div class="form-group">
                        <label for="nom" class="form-label">
                            <i class="bi bi-pencil-fill"></i>
                            Nom du projet
                        </label>
                        <input 
                            type="text" 
                            id="nom" 
                            name="nom" 
                            class="form-input" 
                            placeholder="Ex: E-commerce API" 
                            value="{{ old('nom') }}"
                            required
                        >
                        <small class="form-hint">Donnez un nom descriptif à votre projet</small>
                    </div>

                    <div class="form-group">
                        <label for="fichier_zip" class="form-label">
                            <i class="bi bi-cloud-upload-fill"></i>
                            Fichier ZIP
                        </label>
                        <div class="file-upload-area" id="dropZone">
                            <input 
                                type="file" 
                                id="fichier_zip" 
                                name="fichier_zip" 
                                class="file-input" 
                                accept=".zip"
                                required
                            >
                            <div class="file-upload-content">
                                <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                <p class="upload-text">Glissez-déposez votre fichier ZIP ici</p>
                                <p class="upload-subtext">ou cliquez pour sélectionner</p>
                                <span class="upload-limit">Taille maximale : 50 MB</span>
                            </div>
                            <div class="file-upload-preview" id="filePreview" style="display: none;">
                                <i class="bi bi-file-zip-fill file-icon"></i>
                                <div class="file-info">
                                    <p class="file-name" id="fileName"></p>
                                    <p class="file-size" id="fileSize"></p>
                                </div>
                                <button type="button" class="btn-remove" onclick="removeFile()">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-info-circle-fill"></i>
                            Type de projet (optionnel)
                        </label>
                        <select name="type_projet" class="form-select">
                            <option value="">-- Sélectionnez --</option>
                            <option value="Backend">Backend (Laravel, Node.js, Python...)</option>
                            <option value="Frontend">Frontend (React, Vue, Angular...)</option>
                            <option value="Fullstack">Fullstack</option>
                            <option value="Mobile">Mobile (React Native, Flutter...)</option>
                            <option value="Machine Learning">Machine Learning / IA</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('user.dashboard') }}'">
                            <i class="bi bi-x-circle"></i>
                            Annuler
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-upload"></i>
                            Lancer l'analyse
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Formulaire GitHub -->
        <div id="githubForm" class="upload-form-container">
            <div class="card-modern">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            <i class="bi bi-github"></i>
                            Importer depuis GitHub
                        </h3>
                        <p class="card-subtitle">Seuls les dépôts publics sont supportés</p>
                    </div>
                </div>
                
                <form action="{{ route('projects.store') }}" method="POST" class="upload-form">
                    @csrf
                    <input type="hidden" name="type_depot" value="GitHub">

                    <div class="form-group">
                        <label for="nom_github" class="form-label">
                            <i class="bi bi-pencil-fill"></i>
                            Nom du projet
                        </label>
                        <input 
                            type="text" 
                            id="nom_github" 
                            name="nom" 
                            class="form-input" 
                            placeholder="Ex: Portfolio React" 
                            value="{{ old('nom') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="lien_depot" class="form-label">
                            <i class="bi bi-link-45deg"></i>
                            URL du dépôt GitHub
                        </label>
                        <input 
                            type="url" 
                            id="lien_depot" 
                            name="lien_depot" 
                            class="form-input" 
                            placeholder="https://github.com/username/repository" 
                            value="{{ old('lien_depot') }}"
                            required
                        >
                        <small class="form-hint">
                            <i class="bi bi-info-circle"></i>
                            Exemple : https://github.com/laravel/laravel
                        </small>
                    </div>

                    <div class="github-info-box">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <strong>Information importante</strong>
                            <p>Seuls les dépôts publics peuvent être analysés. Assurez-vous que votre repository est accessible sans authentification.</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('user.dashboard') }}'">
                            <i class="bi bi-x-circle"></i>
                            Annuler
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-download"></i>
                            Cloner et analyser
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions -->
        <div class="card-modern instructions-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-lightbulb-fill"></i>
                    Comment préparer votre projet ?
                </h3>
            </div>
            <div class="instructions-content">
                <div class="instruction-item">
                    <div class="instruction-number">1</div>
                    <div class="instruction-text">
                        <strong>Organisez votre code</strong>
                        <p>Assurez-vous que votre projet a une structure claire avec tous les fichiers sources</p>
                    </div>
                </div>
                <div class="instruction-item">
                    <div class="instruction-number">2</div>
                    <div class="instruction-text">
                        <strong>Supprimez les fichiers inutiles</strong>
                        <p>Retirez node_modules, vendor, .env et autres fichiers volumineux ou sensibles</p>
                    </div>
                </div>
                <div class="instruction-item">
                    <div class="instruction-number">3</div>
                    <div class="instruction-text">
                        <strong>Compressez en ZIP</strong>
                        <p>Créez une archive ZIP de votre projet (ne dépassant pas 50 MB)</p>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

{{-- Scripts --}}
<script>
// Switch entre ZIP et GitHub
let currentOption = 'zip';

function selectOption(type) {
    currentOption = type;
    
    // Update active cards
    document.getElementById('zipOption').classList.toggle('active', type === 'zip');
    document.getElementById('githubOption').classList.toggle('active', type === 'github');
    
    // Update form visibility
    document.getElementById('zipForm').classList.toggle('active', type === 'zip');
    document.getElementById('githubForm').classList.toggle('active', type === 'github');
}

// Drag & Drop
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fichier_zip');
const filePreview = document.getElementById('filePreview');
const uploadContent = document.querySelector('.file-upload-content');

// Prevent defaults
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Highlight drop zone
['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.add('drag-over');
    });
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.remove('drag-over');
    });
});

// Handle drop
dropZone.addEventListener('drop', (e) => {
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        handleFiles(files[0]);
    }
});

// Handle file selection
fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFiles(e.target.files[0]);
    }
});

// Display file info
function handleFiles(file) {
    if (!file.name.endsWith('.zip')) {
        alert('Veuillez sélectionner un fichier ZIP');
        return;
    }
    
    const fileSize = (file.size / (1024 * 1024)).toFixed(2);
    
    if (file.size > 50 * 1024 * 1024) {
        alert('Le fichier est trop volumineux (max 50 MB)');
        return;
    }
    
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = fileSize + ' MB';
    
    uploadContent.style.display = 'none';
    filePreview.style.display = 'flex';
}

// Remove file
function removeFile() {
    fileInput.value = '';
    uploadContent.style.display = 'block';
    filePreview.style.display = 'none';
}
</script>

{{-- Styles --}}
<style>
:root {
    --bg-dark: #0a0a0c;
    --card-bg: #131316;
    --primary-accent: #3b82f6;
    --secondary-accent: #10b981;
    --danger-accent: #ef4444;
    --warning-accent: #f59e0b;
    --text-main: #f4f4f5;
    --text-muted: #a1a1aa;
    --border-color: #27272a;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.bg-dark {
    background-color: var(--bg-dark);
}

/* SIDEBAR - Same as dashboard */
.sidebar {
    width: 280px;
    background: var(--card-bg);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    height: 100vh;
    position: sticky;
    top: 0;
}

.sidebar-nav {
    flex: 1;
    padding: 24px 16px;
    overflow-y: auto;
}

.nav-btn {
    width: 100%;
    padding: 12px 16px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
    text-decoration: none;
}

.nav-btn i {
    font-size: 1.1rem;
}

.nav-btn:hover {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-accent);
}

.nav-btn.active {
    background: rgba(59, 130, 246, 0.15);
    color: var(--primary-accent);
}

.sidebar-footer {
    padding: 16px;
    border-top: 1px solid var(--border-color);
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: rgba(59, 130, 246, 0.05);
    border-radius: 10px;
    margin-bottom: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-accent);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    text-transform: uppercase;
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-main);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-email {
    font-size: 0.75rem;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-logout {
    width: 100%;
    padding: 10px 16px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    border-radius: 8px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-accent);
}

/* MAIN CONTENT */
.main-content {
    flex: 1;
    padding: 40px;
    overflow-y: auto;
    max-width: 100%;
}

.page-header {
    margin-bottom: 32px;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-main);
    margin-bottom: 4px;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
}

/* ALERTS */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 0.95rem;
}

.alert i {
    font-size: 1.2rem;
    margin-top: 2px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    color: var(--secondary-accent);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger-accent);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.error-list {
    margin: 0;
    padding-left: 20px;
}

.error-list li {
    margin: 4px 0;
}

/* UPLOAD OPTIONS */
.upload-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-bottom: 40px;
}

.option-card {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 32px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.option-card:hover {
    border-color: var(--primary-accent);
    transform: translateY(-4px);
}

.option-card.active {
    border-color: var(--primary-accent);
    background: rgba(59, 130, 246, 0.05);
}

.option-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin: 0 auto 20px;
}

.option-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 8px;
}

.option-description {
    color: var(--text-muted);
    font-size: 0.95rem;
    margin-bottom: 16px;
}

.option-badge {
    display: inline-block;
    padding: 6px 16px;
    background: rgba(16, 185, 129, 0.15);
    color: var(--secondary-accent);
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.option-badge-github {
    background: rgba(245, 158, 11, 0.15);
    color: var(--warning-accent);
}

/* UPLOAD FORM CONTAINER */
.upload-form-container {
    display: none;
    margin-bottom: 32px;
}

.upload-form-container.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* CARD */
.card-modern {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-subtitle {
    font-size: 0.85rem;
    color: var(--text-muted);
}

/* FORM */
.upload-form {
    padding: 32px;
}

.form-group {
    margin-bottom: 28px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 12px;
    font-size: 0.95rem;
}

.form-input, .form-select {
    width: 100%;
    padding: 14px 16px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-main);
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: var(--primary-accent);
    background: rgba(59, 130, 246, 0.05);
}

.form-hint {
    display: block;
    margin-top: 8px;
    font-size: 0.85rem;
    color: var(--text-muted);
}

/* FILE UPLOAD AREA */
.file-upload-area {
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    cursor: pointer;
}

.file-upload-area.drag-over {
    border-color: var(--primary-accent);
    background: rgba(59, 130, 246, 0.05);
}

.file-input {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.upload-icon {
    font-size: 3rem;
    color: var(--primary-accent);
    margin-bottom: 16px;
}

.upload-text {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 8px;
}

.upload-subtext {
    color: var(--text-muted);
    margin-bottom: 12px;
}

.upload-limit {
    display: inline-block;
    padding: 4px 12px;
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning-accent);
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* FILE PREVIEW */
.file-upload-preview {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: rgba(59, 130, 246, 0.05);
    border-radius: 10px;
}

.file-icon {
    font-size: 2.5rem;
    color: var(--primary-accent);
}

.file-info {
    flex: 1;
    text-align: left;
}

.file-name {
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 4px;
}

.file-size {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.btn-remove {
    width: 36px;
    height: 36px;
    border: none;
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-accent);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.btn-remove:hover {
    background: rgba(239, 68, 68, 0.2);
}

/* GITHUB INFO BOX */
.github-info-box {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: rgba(59, 130, 246, 0.05);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
    margin-bottom: 24px;
}

.github-info-box i {
    font-size: 1.5rem;
    color: var(--primary-accent);
    margin-top: 2px;
}

.github-info-box strong {
    color: var(--text-main);
    display: block;
    margin-bottom: 4px;
}

.github-info-box p {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin: 0;
}

/* BUTTONS */
.form-actions {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    margin-top: 32px;
}

.btn-primary, .btn-secondary {
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
}

.btn-primary {
    background: var(--primary-accent);
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.btn-secondary {
    background: transparent;
    color: var(--text-main);
    border: 1px solid var(--border-color);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.05);
}

/* INSTRUCTIONS CARD */
.instructions-card {
    margin-top: 32px;
}

.instructions-content {
    padding: 24px;
}

.instruction-item {
    display: flex;
    gap: 20px;
    margin-bottom: 24px;
}

.instruction-item:last-child {
    margin-bottom: 0;
}

.instruction-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-accent);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.instruction-text strong {
    color: var(--text-main);
    display: block;
    margin-bottom: 4px;
}

.instruction-text p {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin: 0;
}

/* RESPONSIVE */
@media (max-width: 968px) {
    .upload-options {
        grid-template-columns: 1fr;
    }

    .sidebar {
        width: 240px;
    }

    .main-content {
        padding: 24px 16px;
    }
}

@media (max-width: 640px) {
    .form-actions {
        flex-direction: column-reverse;
    }

    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection