@extends('layouts.app')
@section('title', 'Administration système')
@section('content')

<style>
    .header-admin {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .header-left h1 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .header-left p {
        font-size: 13px;
        color: var(--muted);
    }
    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .status-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--green-bg);
        color: var(--green);
        padding: 8px 14px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        background: var(--green);
        border-radius: 50%;
    }
    .notification-icon {
        cursor: pointer;
        font-size: 18px;
    }
    
    .kpi-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .kpi-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: var(--shadow);
    }
    .kpi-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 8px;
    }
    .kpi-value {
        font-family: 'Sora', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: var(--navy);
    }
    
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 28px 0 16px;
    }
    .section-header h2 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }
    .link-view {
        font-size: 12px;
        color: var(--navy-2);
        text-decoration: none;
        font-weight: 700;
    }
    
    .agencies-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 14px;
        margin-bottom: 28px;
    }
    .agency-card {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 14px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .agency-card:hover {
        border-color: var(--navy-2);
        box-shadow: var(--shadow);
    }
    .agency-icon {
        font-size: 28px;
        margin-bottom: 8px;
    }
    .agency-name {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .agency-stats {
        font-size: 11px;
        color: var(--muted);
        margin-bottom: 6px;
    }
    .agency-status {
        display: inline-flex;
        padding: 3px 8px;
        border-radius: 100px;
        font-size: 10px;
        font-weight: 700;
    }
    .status-normal { background: #e8f6ef; color: #1e8a5f; }
    .status-warning { background: #fdf3e2; color: #c97f1e; }
    .status-alert { background: #fbeae8; color: #c4453b; }
    
    .chart-container {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px;
        box-shadow: var(--shadow);
        margin-bottom: 28px;
    }
    .chart-wrapper {
        position: relative;
        height: 280px;
    }
    
    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    
    .table-container {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        margin-bottom: 28px;
    }
    .table-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--line);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .table-header h3 {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
    }
    
    .action-badge {
        display: inline-flex;
        padding: 3px 9px;
        border-radius: 100px;
        font-size: 10px;
        font-weight: 700;
    }
    .action-authorized { background: #e8f6ef; color: #1e8a5f; }
    .action-config { background: #e8edfb; color: #0a3a8f; }
    .action-blocked { background: #fbeae8; color: #c4453b; }
    
    @media(max-width: 1200px) {
        .kpi-cards { grid-template-columns: repeat(2, 1fr); }
        .agencies-grid { grid-template-columns: repeat(4, 1fr); }
        .grid-2 { grid-template-columns: 1fr; }
    }
    
    @media(max-width: 768px) {
        .kpi-cards { grid-template-columns: 1fr; }
        .agencies-grid { grid-template-columns: repeat(2, 1fr); }
        .header-admin { flex-direction: column; align-items: flex-start; }
        .header-right { width: 100%; margin-top: 12px; }
    }
</style>

<div class="header-admin">
    <div class="header-left">
        <h1>Administration système</h1>
        <p>Vue globale — {{ $nbAgences }} agences</p>
    </div>
    <div class="header-right">
        <div class="status-badge">
            <div class="status-dot"></div>
            Système opérationnel
        </div>
        <div class="notification-icon">🔔</div>
    </div>
</div>

<!-- KPI Cards -->
<div class="kpi-cards">
    <div class="kpi-card">
        <div class="kpi-label">Sociétaires (réseau)</div>
        <div class="kpi-value">{{ number_format($nbSocietaires, 0, ',', ' ') }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Agences actives</div>
        <div class="kpi-value">{{ $nbAgences }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Collaborateurs</div>
        <div class="kpi-value">{{ $nbUtilisateurs }}</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Tentatives de connexion échouées (24h)</div>
        <div class="kpi-value" style="color: var(--red);">{{ $connexionsEchouees }}</div>
    </div>
</div>

<!-- Vue Multi-Agences -->
<div class="section-header">
    <h2>Vue multi-agences</h2>
    <a href="{{ route('admin.agences.index') }}" class="link-view">Territoire togolais</a>
</div>

<div class="agencies-grid">
    @foreach($agences->take(6) as $agence)
        <div class="agency-card" onclick="openAgenceModal({{ $agence->id }})" style="cursor: pointer;">
            <div class="agency-icon">🏢</div>
            <div class="agency-name">{{ substr($agence->nom, 0, 20) }}</div>
            <div class="agency-stats">
                {{ $agence->societaires_count }} sociétaires · PAM {{ rand(1, 5) }}%
            </div>
            <div class="agency-status status-normal">
                @if(rand(0, 1))
                    ✓ Fonctionnement normal
                @else
                    ⚠ Incident signalé
                @endif
            </div>
        </div>
    @endforeach
</div>

@include('partials.agence-modal')

<!-- Activité Chart -->
<div class="section-header">
    <h2>Activité — top 5 agences (30 derniers jours)</h2>
</div>

<div class="chart-container">
    <div class="chart-wrapper">
        <canvas id="activityChart"></canvas>
    </div>
</div>

<!-- Gestion des utilisateurs et Journal d'activité -->
<div class="grid-2">
    <!-- Gestion des utilisateurs -->
    <div>
        <div class="section-header" style="margin-top: 0;">
            <h2>Gestion des utilisateurs</h2>
            <a href="{{ route('admin.users.index') }}" class="link-view">+ Ajouter</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: left;">Rôle</th>
                        <th style="text-align: right;">Comptes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Gérants d'agence</td>
                        <td style="text-align: right; font-weight: 700;">{{ $utilisateursParlRole->get('Gérants d\'agence', 0) }}</td>
                    </tr>
                    <tr>
                        <td>Agents de crédit</td>
                        <td style="text-align: right; font-weight: 700;">{{ $utilisateursParlRole->get('Agents de crédit', 0) }}</td>
                    </tr>
                    <tr>
                        <td>Agents de promotion</td>
                        <td style="text-align: right; font-weight: 700;">{{ $utilisateursParlRole->get('Agents de promotion', 0) }}</td>
                    </tr>
                    <tr>
                        <td>Cassiers</td>
                        <td style="text-align: right; font-weight: 700;">{{ $utilisateursParlRole->get('Cassiers', 0) }}</td>
                    </tr>
                    <tr>
                        <td>Comptables</td>
                        <td style="text-align: right; font-weight: 700;">{{ $utilisateursParlRole->get('Comptables', 0) }}</td>
                    </tr>
                    <tr>
                        <td>Sociétaires (accès en ligne)</td>
                        <td style="text-align: right; font-weight: 700;">{{ number_format($nbSocietaires, 0, ',', ' ') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Journal d'activité & sécurité -->
    <div>
        <div class="section-header" style="margin-top: 0;">
            <h2>Journal d'activité & sécurité</h2>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 12%;">Heure</th>
                        <th style="width: 28%;">Utilisateur</th>
                        <th style="width: 35%;">Action</th>
                        <th style="width: 25%;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journalActivite as $entree)
                        <tr>
                            <td style="font-size: 12px;">{{ $entree->date_action->format('H:i') }}</td>
                            <td style="font-size: 12px;">{{ $entree->utilisateur?->nomComplet() ?? 'système' }}</td>
                            <td style="font-size: 12px;">{{ $entree->description ?? $entree->action }}</td>
                            <td>
                                @if($entree->statut === 'succes')
                                    <span class="action-badge action-authorized">✓ Autorisée</span>
                                @else
                                    <span class="action-badge action-blocked">✕ Bloquée</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--muted); padding: 20px;">Aucun enregistrement</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: #d0d7e8;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #b9c6ea;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activityChart').getContext('2d');
        
        const data = {
            labels: @json($activiteLabels),
            datasets: [
                @foreach($activiteData as $index => $dataset)
                    {
                        label: '{{ $dataset["label"] }}',
                        data: @json($dataset["data"]),
                        borderColor: @json($index === 0 ? '#011f62' : ($index === 1 ? '#0a3a8f' : ($index === 2 ? '#e8a33d' : ($index === 3 ? '#1e8a5f' : '#c4453b')))),
                        backgroundColor: 'transparent',
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: false,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: @json($index === 0 ? '#011f62' : ($index === 1 ? '#0a3a8f' : ($index === 2 ? '#e8a33d' : ($index === 3 ? '#1e8a5f' : '#c4453b')))),
                    },
                @endforeach
            ]
        };
        
        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: '600',
                                family: "'Manrope', sans-serif"
                            },
                            color: '#101a2e',
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        }
                    },
                    filler: {
                        propagate: true
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: true,
                            color: '#e6e9f2',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#5c6479',
                            font: {
                                size: 11,
                            }
                        }
                    },
                    y: {
                        display: true,
                        grid: {
                            display: true,
                            color: '#e6e9f2',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#5c6479',
                            font: {
                                size: 11,
                            },
                            beginAtZero: true
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection
