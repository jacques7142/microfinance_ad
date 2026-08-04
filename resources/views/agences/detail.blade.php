@extends('layouts.app')
@section('title', 'Détails Agence - ' . $agence->nom)
@section('content')

<style>
    .agence-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-2) 100%);
        color: #fff;
        padding: 28px;
        border-radius: var(--radius);
        margin-bottom: 28px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 20px;
        align-items: center;
    }
    
    .agence-header-info h1 {
        font-size: 24px;
        margin-bottom: 8px;
    }
    
    .agence-header-info p {
        font-size: 13px;
        opacity: 0.9;
        margin: 4px 0;
    }
    
    .agence-status {
        display: inline-flex;
        padding: 8px 16px;
        border-radius: 100px;
        font-weight: 700;
        font-size: 12px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }
    
    .agence-status.actif {
        background: rgba(30, 138, 95, 0.3);
        color: #fff;
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }
    
    .card-section {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: var(--shadow);
    }
    
    #map {
        width: 100%;
        height: 400px;
        border-radius: 10px;
        margin-bottom: 16px;
        border: 1px solid var(--line);
    }
    
    .map-info {
        font-size: 12px;
        color: var(--muted);
        padding: 8px;
        background: var(--bg);
        border-radius: 8px;
        margin-top: 12px;
    }
    
    .info-block {
        margin-bottom: 20px;
    }
    
    .info-block:last-child {
        margin-bottom: 0;
    }
    
    .info-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    
    .info-content {
        font-size: 14px;
        color: var(--ink);
        line-height: 1.6;
    }
    
    .gerant-card {
        background: linear-gradient(135deg, var(--bg), #fff);
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 16px;
        margin-top: 12px;
    }
    
    .gerant-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .gerant-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--navy);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        font-family: 'Sora';
    }
    
    .gerant-info h3 {
        font-size: 13px;
        font-weight: 700;
        margin: 0;
    }
    
    .gerant-info p {
        font-size: 11px;
        color: var(--muted);
        margin: 2px 0;
    }
    
    .contact-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 14px;
        background: var(--navy);
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .contact-button:hover {
        background: var(--navy-2);
    }
    
    .horaires-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .horaires-list li {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--line);
        font-size: 12px;
    }
    
    .horaires-list li:last-child {
        border-bottom: none;
    }
    
    .horaires-list strong {
        color: var(--navy);
        min-width: 80px;
    }
    
    .badge-secteur {
        display: inline-block;
        background: var(--navy-2);
        color: #fff;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 8px;
    }
    
    .stats-mini {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 12px;
    }
    
    .stat-mini {
        background: var(--bg);
        padding: 12px;
        border-radius: 8px;
        text-align: center;
    }
    
    .stat-mini .label {
        font-size: 10px;
        color: var(--muted);
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .stat-mini .value {
        font-size: 18px;
        font-weight: 700;
        color: var(--navy);
        font-family: 'Sora';
    }
    
    @media(max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
        .agence-header {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Header -->
<div class="agence-header">
    <div class="agence-header-info">
        <h1>{{ $agence->nom }}</h1>
        <p><x-icon name="map-pin" size="16"/> {{ $agence->ville }}, {{ $agence->secteur ?? 'Togo' }}</p>
        <p><x-icon name="calendar" size="16"/> Ouverture: {{ $agence->date_ouverture->format('d M Y') }}</p>
        <div style="margin-top: 12px;">
            @if($agence->est_siege)
                <span class="badge b-navy"><x-icon name="building" size="14"/> Direction Générale</span>
            @endif
            <span class="agence-status {{ $agence->actif ? 'actif' : '' }}">
                {!! $agence->actif ? '<x-icon name="check" size="14"/> Actif' : '<x-icon name="x" size="14"/> Inactif' !!}
            </span>
        </div>
    </div>
    <div style="text-align: right;">
        <div style="margin-bottom: 8px;"><x-icon name="building" size="40"/></div>
    </div>
</div>

<!-- Contenu Principal -->
<div class="content-grid">
    <!-- Colonne Gauche: Carte et Description -->
    <div>
        <!-- Carte -->
        <div class="card-section">
            <div class="info-title"><x-icon name="map-pin" size="14"/> Localisation</div>
            <div id="map"></div>
            <div class="map-info">
                Latitude: {{ $agence->latitude ?? 'Non définie' }} | 
                Longitude: {{ $agence->longitude ?? 'Non définie' }}
            </div>
        </div>
        
        <!-- Description et Horaires -->
        <div class="card-section" style="margin-top: 24px;">
            <div class="info-title"><x-icon name="list" size="14"/> À Propos</div>
            <div class="info-content">
                {{ $agence->description ?? 'Aucune description disponible.' }}
            </div>
            
            @if($agence->horaires_fonctionnement)
                <div style="margin-top: 20px;">
                    <div class="info-title"><x-icon name="tontine" size="14"/> Horaires de Fonctionnement</div>
                    <ul class="horaires-list">
                        @foreach($agence->horaires_fonctionnement as $jour => $horaires)
                            <li>
                                <strong>{{ ucfirst($jour) }}</strong>
                                <span>{{ $horaires['ouverture'] ?? '—' }} - {{ $horaires['fermeture'] ?? '—' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Colonne Droite: Chef d'Agence et Stats -->
    <div>
        <!-- Chef d'Agence -->
        <div class="card-section">
            <div class="info-title"><x-icon name="briefcase" size="14"/> Chef d'Agence</div>
            
            @if($gerant)
                <div class="gerant-card">
                    <div class="gerant-header">
                        <div class="gerant-avatar">
                            {{ strtoupper(substr($gerant->prenom, 0, 1) . substr($gerant->nom, 0, 1)) }}
                        </div>
                        <div class="gerant-info">
                            <h3>{{ $gerant->nomComplet() }}</h3>
                            <p>{{ ucfirst(str_replace('_', ' ', $gerant->role)) }}</p>
                        </div>
                    </div>
                    
                    <div class="info-block">
<div class="info-title" style="margin-bottom: 6px;"><x-icon name="email" size="14"/> Email</div>
                        <a href="mailto:{{ $gerant->email }}" class="contact-button" style="width: 100%; text-align: center;">
                            <x-icon name="email" size="14"/> {{ $gerant->email }}
                        </a>
                    </div>

                    @if($gerant->telephone)
                        <div class="info-block">
                            <div class="info-title" style="margin-bottom: 6px;"><x-icon name="phone" size="14"/> Téléphone</div>
                            <a href="tel:{{ $gerant->telephone }}" class="contact-button" style="width: 100%; text-align: center;">
                                <x-icon name="phone" size="14"/> {{ $gerant->telephone }}
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div style="padding: 16px; background: var(--bg); border-radius: 8px; text-align: center; color: var(--muted);">
                    Aucun gérant assigné
                </div>
            @endif
        </div>
        
        <!-- Informations Agence -->
        <div class="card-section" style="margin-top: 24px;">
            <div class="info-title"><x-icon name="chart" size="14"/> Statistiques</div>
            <div class="stats-mini">
                <div class="stat-mini">
                    <div class="label">Sociétaires</div>
                    <div class="value">{{ $agence->societaires_count ?? 0 }}</div>
                </div>
                <div class="stat-mini">
                    <div class="label">Transactions</div>
                    <div class="value">{{ $agence->transactions_count ?? 0 }}</div>
                </div>
            </div>
        </div>
        
        <!-- Contact Agence -->
        <div class="card-section" style="margin-top: 24px;">
<div class="info-title"><x-icon name="phone" size="14"/> Contacter l'Agence</div>

            @if($agence->telephone_agence)
                <a href="tel:{{ $agence->telephone_agence }}" class="contact-button" style="width: 100%; text-align: center; margin-bottom: 10px;">
                    <x-icon name="phone" size="14"/> {{ $agence->telephone_agence }}
                </a>
            @endif

            <a href="https://www.google.com/maps/search/{{ urlencode($agence->nom . ' ' . $agence->ville) }}"
               target="_blank" class="contact-button" style="width: 100%; text-align: center;">
                <x-icon name="map-pin" size="14"/> Google Maps
            </a>
        </div>
        
        <!-- Adresse -->
        <div class="card-section" style="margin-top: 24px;">
            <div class="info-title"><x-icon name="map-pin" size="14"/> Adresse</div>
            <div class="info-content">
                {{ $agence->adresse }}
            </div>
            @if($agence->secteur)
                <span class="badge-secteur">{{ $agence->secteur }}</span>
            @endif
        </div>
    </div>
</div>

<!-- Bouton Retour -->
<div style="margin-top: 28px;">
    <a href="{{ route('dashboard') }}" class="btn btn-ghost"><x-icon name="arrow-right" size="14" style="transform: rotate(180deg);"/> Retour au tableau de bord</a>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = {{ $coordonnees['lat'] }};
    const lng = {{ $coordonnees['lng'] }};
    const agenceName = "{{ $agence->nom }}";
    
    // Initialiser la carte
    const map = L.map('map').setView([lat, lng], 13);
    
    // Ajouter les tiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);
    
    // Ajouter un marqueur
    const marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup(`<strong>${agenceName}</strong><br>${lat.toFixed(4)}, ${lng.toFixed(4)}`).openPopup();
    
    // Ajouter un cercle de rayon
    L.circle([lat, lng], {
        color: '#011f62',
        fillColor: '#0a3a8f',
        fillOpacity: 0.1,
        radius: 500,
    }).addTo(map);
});
</script>
@endpush

@endsection
