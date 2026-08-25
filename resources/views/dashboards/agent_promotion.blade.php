@extends('layouts.app')
@section('title', 'Agent de promotion — Tontine LOGOKU')
@section('content')

<style>
.chart-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.chart-card h3{font-size:14px;margin:0 0 4px;}
.chart-card .hint{font-size:11.5px;color:var(--muted);margin:0 0 14px;}
.chart-wrap{position:relative;height:230px;}
.charts-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;}
@media(max-width:900px){.charts-grid{grid-template-columns:1fr;}}
</style>

<div class="grid g3">
  <div class="card stat card-pad"><div class="lbl">Collecté aujourd'hui</div><div class="val">{{ number_format($totalCollecte,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Passages effectués</div><div class="val">{{ $collectesDuJour->count() }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Zone de tournée</div><div class="val" style="font-size:16px;">{{ auth()->user()->zone_tournee ?? '—' }}</div></div>
</div>

<div class="charts-grid">
  <div class="chart-card">
    <h3>Collectes — 7 derniers jours</h3>
    <p class="hint">Montants collectés (F CFA) sur la semaine.</p>
    <div class="chart-wrap"><canvas id="chartCollectes"></canvas></div>
  </div>
  <div class="chart-card">
    <h3>Modes de confirmation du mois</h3>
    <p class="hint">Répartition des collectes selon la confirmation.</p>
    <div class="chart-wrap"><canvas id="chartModes"></canvas></div>
  </div>
</div>

<div class="section-title"><h2>Collectes du jour</h2><a href="{{ route('tontine.index') }}" class="btn btn-navy btn-sm">Ouvrir ma tournée</a></div>
<div class="card">
  <table>
    <tr><th>Membre</th><th>Montant</th><th>Lieu</th><th>Heure</th></tr>
    @forelse($collectesDuJour as $c)
      <tr>
        <td>{{ $c->compteTontine->societaire->nomComplet() }}</td>
        <td>{{ number_format($c->montant,0,',',' ') }} F</td>
        <td>{{ $c->lieu ?? '—' }}</td>
        <td>{{ $c->date_collecte->format('H:i') }}</td>
      </tr>
    @empty
      <tr><td colspan="4" style="color:#5c6479;">Aucune collecte enregistrée aujourd'hui.</td></tr>
    @endforelse
  </table>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var c1 = document.getElementById('chartCollectes');
    if (c1 && @json($collectes7Jours).some(function (v) { return v > 0; })) {
        new Chart(c1, {
            type: 'bar',
            data: {
                labels: @json($labels7Jours),
                datasets: [{ label: 'Collectes (F)', data: @json($collectes7Jours), backgroundColor: 'rgba(232,163,61,.85)', borderRadius: 8, maxBarThickness: 42 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#5c6479', font: { size: 11 } } },
                    y: { beginAtZero: true, grid: { color: '#e6e9f2' }, ticks: { color: '#5c6479', font: { size: 11 } } }
                }
            }
        });
    } else if (c1) {
        c1.parentElement.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12.5px;">Aucune collecte cette semaine.</div>';
    }

    var c2 = document.getElementById('chartModes');
    var modes = @json($repartitionModes->pluck('total'));
    if (c2 && modes.some(function (v) { return v > 0; })) {
        new Chart(c2, {
            type: 'doughnut',
            data: {
                labels: @json($repartitionModes->pluck('label')),
                datasets: [{ data: modes, backgroundColor: ['#1e8a5f', '#0a3a8f', '#e8a33d', '#c4453b'], borderWidth: 2, borderColor: 'var(--surface)' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '62%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, boxWidth: 8, font: { family: "'Manrope', sans-serif", size: 11 } } } }
            }
        });
    } else if (c2) {
        c2.parentElement.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12.5px;">Aucune collecte ce mois.</div>';
    }
});
</script>
@endpush
@endsection