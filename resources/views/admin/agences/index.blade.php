@extends('layouts.app')
@section('title', "Réseau d'agences")
@section('content')

<style>
    .agence-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .agence-header h1 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .agence-header p {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .ma-wrap {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 20px;
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
        flex: 0 0 220px;
        width: 220px;
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
        transform: translateY(-2px);
    }
    .agency-icon {
        font-size: 26px;
        color: var(--navy-2);
        margin-bottom: 8px;
    }
    .agency-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--navy);
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
    @media(max-width: 700px) {
        .agence-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .ma-panel-hint { display: none; }
        .ma-agencies .agency-card { flex-basis: 170px; width: 170px; }
    }
</style>

<div class="agence-header">
    <div>
        <h1><x-icon name="building" size="22"/> Réseau d'agences</h1>
        <p>{{ $agences->count() }} agence(s) réparties sur l'ensemble du territoire togolais</p>
    </div>
    <a href="{{ route('admin.agences.create') }}" class="btn btn-navy"><x-icon name="plus" size="16"/> Nouvelle agence</a>
</div>

<div class="ma-wrap">
    <div class="ma-tabs" id="maTabs">
        @foreach($regionsAgences as $groupe)
            <button type="button"
                    class="ma-tab {{ $loop->first ? 'active' : '' }}"
                    onmouseenter="showMaPanel({{ $loop->index }})"
                    onfocus="showMaPanel({{ $loop->index }})">
                <span class="ma-tab-name">{{ $groupe['region'] }}</span>
                <span class="ma-tab-count">{{ $groupe['agences']->count() }}</span>
            </button>
        @endforeach
    </div>

    <div class="ma-panels">
        @foreach($regionsAgences as $groupe)
            <div class="ma-panel {{ $loop->first ? 'active' : '' }}">
                <div class="ma-panel-head">
                    <span class="ma-panel-region">{{ $groupe['region'] }}</span>
                    <span class="ma-panel-sub">{{ $groupe['agences']->count() }} agence{{ $groupe['agences']->count() > 1 ? 's' : '' }}</span>
                    <span class="ma-panel-hint"><x-icon name="info" size="13"/> Survolez les régions ci-dessus</span>
                </div>
                @if($groupe['agences']->isNotEmpty())
                    <div class="ma-agencies">
                        @foreach($groupe['agences'] as $a)
                            <div class="agency-card" onclick="openAgenceModal({{ $a->id }})" title="{{ $a->nom }}">
                                <div class="agency-icon"><x-icon name="building" size="24"/></div>
                                <div class="agency-name">{{ \Illuminate\Support\Str::limit($a->nom, 24) }}</div>
                                <div class="agency-stats">
                                    {{ $a->ville }} · {{ $a->societaires_count }} sociétaire{{ $a->societaires_count > 1 ? 's' : '' }}
                                </div>
                                <div class="agency-status {{ $a->est_siege ? 'status-warning' : ($a->actif ? 'status-normal' : 'status-alert') }}">
                                    {{ $a->est_siege ? 'Direction Générale' : ($a->actif ? 'Active' : 'Inactive') }}
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

@push('scripts')
<script>
    function showMaPanel(index) {
        document.querySelectorAll('.ma-panel').forEach((p, i) => p.classList.toggle('active', i === index));
        document.querySelectorAll('.ma-tab').forEach((t, i) => t.classList.toggle('active', i === index));
    }
</script>
@endpush

@endsection
