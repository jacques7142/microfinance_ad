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
    .region-block {
        margin-bottom: 26px;
    }
    .region-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }
    .region-name {
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--navy);
    }
    .region-count {
        display: inline-flex;
        padding: 3px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        background: #e8edfb;
        color: var(--navy-2);
    }
    /* Vue multi-agences : régions horizontales + agences au survol */
    .ma-wrap {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 20px;
        margin-bottom: 28px;
    }
    .ma-tabs {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 10px;
        -webkit-overflow-scrolling: touch;
    }
    .ma-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        padding: 10px 16px;
        border-radius: 100px;
        border: 1px solid var(--line);
        background: var(--bg);
        cursor: pointer;
        transition: all 0.25s ease;
        font-family: 'Sora', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--navy);
    }
    .ma-tab:hover {
        border-color: var(--navy-2);
        background: #eef2fc;
    }
    .ma-tab.active {
        background: var(--navy);
        border-color: var(--navy);
        color: #fff;
    }
    .ma-tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        background: #e8edfb;
        color: var(--navy-2);
    }
    .ma-tab.active .ma-tab-count {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }
    .ma-panels {
        margin-top: 14px;
        border-top: 1px solid var(--line);
        padding-top: 16px;
    }
    .ma-panel {
        display: none;
        animation: maFade 0.25s ease;
    }
    .ma-panel.active {
        display: block;
    }
    @keyframes maFade {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .ma-panel-head {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .ma-panel-region {
        font-family: 'Sora', sans-serif;
        font-size: 16px;
        font-weight: 800;
        color: var(--navy);
    }
    .ma-panel-sub {
        display: inline-flex;
        padding: 3px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        background: #e8edfb;
        color: var(--navy-2);
    }
    .ma-panel-hint {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
    }
    .ma-agencies {
        display: flex;
        flex-direction: row;
        gap: 12px;
        overflow-x: auto;
        padding: 4px 2px 10px;
        -webkit-overflow-scrolling: touch;
    }
    .ma-agencies::-webkit-scrollbar {
        height: 8px;
    }
    .ma-agencies::-webkit-scrollbar-thumb {
        background: #d0d7e8;
        border-radius: 4px;
    }
    .ma-agencies::-webkit-scrollbar-thumb:hover {
        background: #b9c6ea;
    }
    .ma-agencies .agency-card {
        flex: 0 0 200px;
        width: 200px;
    }
    @media(max-width: 700px) {
        .ma-panel-hint { display: none; }
        .ma-agencies .agency-card { flex-basis: 160px; width: 160px; }
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
        <h1><x-icon name="dashboard" size="24"/> Administration système</h1>
        <p><x-icon name="building" size="14"/> {{ $nbAgences }} agences</p>
    </div>
    <div class="header-right">
        <div class="status-badge">
            <div class="status-dot"></div>
            <x-icon name="check" size="14"/> Système opérationnel
        </div>
        <div class="notification-icon"><x-icon name="bell" size="20"/></div>
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
    <h2>Vue multi-agences — réseau COOPEC-AD</h2>
    <a href="{{ route('admin.agences.index') }}" class="link-view">Territoire togolais</a>
</div>

<div class="ma-wrap">
    <div class="ma-tabs" id="maTabs">
        @foreach($regionsAgences as $groupe)
            <button type="button"
                    class="ma-tab {{ $loop->first ? 'active' : '' }}"
                    data-ma-panel="{{ $loop->index }}"
                    onmouseenter="showMaPanel({{ $loop->index }})"
                    onfocus="showMaPanel({{ $loop->index }})">
                <span class="ma-tab-name">{{ $groupe['region'] }}</span>
                <span class="ma-tab-count">{{ $groupe['agences']->count() }}</span>
            </button>
        @endforeach
    </div>

    <div class="ma-panels" id="maPanels">
        @foreach($regionsAgences as $groupe)
            <div class="ma-panel {{ $loop->first ? 'active' : '' }}" data-ma-panel-body="{{ $loop->index }}">
                <div class="ma-panel-head">
                    <span class="ma-panel-region">{{ $groupe['region'] }}</span>
                    <span class="ma-panel-sub">{{ $groupe['agences']->count() }} agence{{ $groupe['agences']->count() > 1 ? 's' : '' }}</span>
                    <span class="ma-panel-hint"><x-icon name="info" size="13"/> Survolez les régions ci-dessus</span>
                </div>
                @if($groupe['agences']->isNotEmpty())
                    <div class="ma-agencies">
                        @foreach($groupe['agences'] as $agence)
                            <div class="agency-card" onclick="openAgenceModal({{ $agence->id }})" style="cursor: pointer;" title="{{ $agence->nom }}">
                                <div class="agency-icon"><x-icon name="building" size="24"/></div>
                                <div class="agency-name">{{ \Illuminate\Support\Str::limit($agence->nom, 22) }}</div>
                                <div class="agency-stats">
                                    {{ $agence->societaires_count }} sociétaire{{ $agence->societaires_count > 1 ? 's' : '' }} · {{ $agence->ville }}
                                </div>
                                <div class="agency-status {{ $agence->actif ? 'status-normal' : 'status-alert' }}">
                                    @if($agence->actif)
                                        <x-icon name="check" size="12"/> Active
                                    @else
                                        <x-icon name="x" size="12"/> Inactive
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card card-pad" style="text-align:center;color:var(--muted);">Aucune agence dans cette région.</div>
                @endif
            </div>
        @endforeach
    </div>
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

<!-- Diagrammes circulaires : collaborateurs & activités -->
<div class="grid-2">
    <div class="chart-container" style="margin-bottom:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h3 style="font-size:14px;margin:0;">Collaborateurs par rôle</h3>
        </div>
        <div style="position:relative;height:250px;">
            <canvas id="rolesChart"></canvas>
        </div>
    </div>
    <div class="chart-container" style="margin-bottom:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h3 style="font-size:14px;margin:0;">Transactions — 30 derniers jours</h3>
        </div>
        <div style="position:relative;height:250px;">
            <canvas id="transactionsChart"></canvas>
        </div>
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
                                    <span class="action-badge action-authorized"><x-icon name="check" size="12"/> Autorisée</span>
                                @else
                                    <span class="action-badge action-blocked"><x-icon name="x" size="12"/> Bloquée</span>
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
    function showMaPanel(index) {
        const panels = document.querySelectorAll('.ma-panel');
        const tabs = document.querySelectorAll('.ma-tab');
        if (panels.length) {
            panels.forEach((p, i) => p.classList.toggle('active', i === index));
        }
        if (tabs.length) {
            tabs.forEach((t, i) => t.classList.toggle('active', i === index));
        }
    }

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

        // --- Diagrammes circulaires ---
        function donut(id, labels, values) {
            var el = document.getElementById(id);
            if (!el || !values.some(function (v) { return v > 0; })) {
                if (el) { el.parentElement.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12.5px;">Aucune donnée.</div>'; }
                return;
            }
            new Chart(el, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{ data: values, backgroundColor: ['#011f62', '#0a3a8f', '#e8a33d', '#1e8a5f', '#c4453b', '#7c3aed', '#0e7490', '#c97f1e'], borderWidth: 2, borderColor: '#fff' }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '62%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, boxWidth: 8, font: { family: "'Manrope', sans-serif", size: 11 }, color: '#5c6479' } },
                        tooltip: { callbacks: { label: function (c) { var total = c.dataset.data.reduce(function (a, b) { return a + b; }, 0); var pct = total > 0 ? Math.round(c.parsed / total * 100) : 0; return ' ' + c.label + ' : ' + c.formattedValue + ' (' + pct + '%)'; } } }
                    }
                }
            });
        }

        donut('rolesChart', @json(collect($rolesCoopec)->pluck('label')), @json(collect($rolesCoopec)->pluck('total')));
        donut('transactionsChart', @json($repartitionTransactions->pluck('label')), @json($repartitionTransactions->pluck('total')));
    });
</script>
@endpush

@endsection
