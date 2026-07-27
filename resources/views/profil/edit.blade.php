@extends('layouts.app')
@section('title', 'Modifier mon profil')
@section('content')

<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .form-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 28px;
        box-shadow: var(--shadow);
    }
    
    .photo-uploader {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--line);
    }
    
    .photo-preview {
        position: relative;
    }
    
    .photo-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--navy);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 700;
        font-family: 'Sora';
        overflow: hidden;
        border: 3px solid var(--gold);
    }
    
    .photo-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .photo-controls {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .upload-label {
        display: inline-block;
        padding: 8px 14px;
        background: var(--navy);
        color: #fff;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        font-size: 12px;
        text-align: center;
    }
    
    .upload-label:hover {
        background: var(--navy-2);
    }
    
    input[type="file"] {
        display: none;
    }
    
    .field {
        margin-bottom: 18px;
    }
    
    .field label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--muted);
        margin-bottom: 6px;
        text-transform: uppercase;
    }
    
    .field input,
    .field textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid var(--line);
        font-family: inherit;
        font-size: 13px;
    }
    
    .field input:focus,
    .field textarea:focus {
        outline: none;
        border-color: var(--navy-2);
        background: #f9fafb;
    }
    
    .field textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--line);
    }
    
    .alert-error {
        background: var(--red-bg);
        color: var(--red);
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: 13px;
    }
    
    .alert-error ul {
        margin: 0;
        padding-left: 20px;
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 24px;">Modifier mon profil</h2>
        
        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Photo Upload -->
            <div class="photo-uploader">
                <div class="photo-preview">
                    <div class="photo-avatar" id="photoPreview">
                        @if($user->photo_profil)
                            <img src="{{ Storage::disk('public')->url($user->photo_profil) }}" alt="Photo profil">
                        @else
                            {{ strtoupper(substr($user->prenom ?? 'U', 0, 1) . substr($user->nom ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                </div>
                
                <div class="photo-controls">
                    <p style="font-size: 13px; margin: 0 0 10px; color: var(--muted);">Photo de profil</p>
                    <label for="photoInput" class="upload-label">📷 Changer la photo</label>
                    <input type="file" id="photoInput" name="photo_profil" accept="image/*" onchange="previewPhoto(event)">
                    <small style="font-size: 11px; color: var(--muted);">Max 5 MB (JPEG, PNG, GIF)</small>
                </div>
            </div>
            
            <!-- Form Fields -->
            <div class="field">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
            </div>
            
            <div class="field">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
            </div>
            
            <div class="field">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            
            <div class="field">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone ?? '') }}">
            </div>
            
            <div class="field">
                <label for="bio">Biographie</label>
                <textarea id="bio" name="bio" placeholder="Parlez un peu de vous...">{{ old('bio', $user->bio ?? '') }}</textarea>
                <small style="color: var(--muted); font-size: 11px;">Maximum 500 caractères</small>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-navy">💾 Enregistrer les modifications</button>
                <a href="{{ route('profil.show') }}" class="btn btn-ghost">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(file);
    }
}
</script>

@endsection
