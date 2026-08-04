@extends('layouts.app')
@section('title', 'Mon profil')
@section('content')

<style>
    .profile-header {
        display: grid;
        grid-template-columns: 140px 1fr;
        gap: 24px;
        margin-bottom: 28px;
        padding: 24px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        align-items: start;
    }
    
    .profile-avatar-container {
        position: relative;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--navy);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: 700;
        font-family: 'Sora';
        overflow: hidden;
        border: 3px solid var(--gold);
    }
    
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-info h2 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .profile-info .meta {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 12px;
    }
    
    .profile-info .role {
        display: inline-block;
        background: var(--navy-2);
        color: #fff;
        padding: 6px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: capitalize;
    }
    
    .profile-info .bio {
        margin-top: 12px;
        font-size: 13px;
        color: var(--ink);
        line-height: 1.6;
    }
    
    .profile-actions {
        display: flex;
        gap: 10px;
        margin-top: 16px;
    }
    
    .card-section {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .info-item {
        padding: 12px;
        background: var(--bg);
        border-radius: 8px;
    }
    
    .info-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    
    .info-value {
        font-size: 14px;
        color: var(--ink);
    }
    
    @media(max-width: 768px) {
        .profile-header {
            grid-template-columns: 1fr;
        }
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="profile-header">
    <div class="profile-avatar-container">
        <div class="profile-avatar">
            @if($user->photo_profil)
                <img src="{{ asset('storage/' . $user->photo_profil) }}" alt="{{ $user->nomComplet() }}">
            @else
                {{ strtoupper(substr($user->prenom ?? 'U', 0, 1) . substr($user->nom ?? 'U', 0, 1)) }}
            @endif
        </div>
    </div>
    
    <div class="profile-info">
        <h2>{{ $user->nomComplet() }}</h2>
        <div class="meta">{{ $user->email }}</div>
        
        @if($authType === 'user')
            <span class="role">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
            @if($user->agence)
                <div class="meta" style="margin-top: 8px;"><x-icon name="map-pin" size="14" style="vertical-align: middle;"/> {{ $user->agence->nom }}</div>
            @endif
        @else
            <span class="role">Sociétaire</span>
            @if($user->agence)
                <div class="meta" style="margin-top: 8px;"><x-icon name="map-pin" size="14" style="vertical-align: middle;"/> {{ $user->agence->nom }}</div>
            @endif
        @endif
        
        @if($user->bio)
            <div class="bio">{{ $user->bio }}</div>
        @endif
        
        <div class="profile-actions">
            <a href="{{ route('profil.edit') }}" class="btn btn-navy btn-sm"><x-icon name="edit" size="14"/> Modifier le profil</a>
        </div>
    </div>
</div>

<div class="card-section">
    <div class="section-title"><x-icon name="list" size="18"/> Informations détaillées</div>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Prénom</div>
            <div class="info-value">{{ $user->prenom }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Nom</div>
            <div class="info-value">{{ $user->nom }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $user->email }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Téléphone</div>
            <div class="info-value">{{ $user->telephone ?? '—' }}</div>
        </div>
        
        @if($authType === 'user')
            <div class="info-item">
                <div class="info-label">Rôle</div>
                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    <span class="badge {{ $user->actif ? 'b-green' : 'b-red' }}">
                        {!! $user->actif ? '<x-icon name="check" size="12"/> Actif' : '<x-icon name="x" size="12"/> Inactif' !!}
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
