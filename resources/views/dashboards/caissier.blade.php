@extends('layouts.app')
@section('title', 'Guichet — Caisse')
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
  <div class="card stat card-pad"><div class="lbl">Dépôts du jour</div><div class="val">{{ number_format($totalDepots,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Retraits du jour</div><div class="val">{{ number_format($totalRetraits,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Opérations traitées</div><div class="val">{{ $operationsDuJour->count() }}</div></div>
</div>

<div class="charts-grid">
  <div class="chart-card">
    <h3>Dépôts vs retraits — 7 derniers jours</h3>
    <p class="hint">Montants traités au guichet (F CFA).</p>
    <div class="chart-wrap"><canvas id="chartFlux"></canvas></div>
  </div>
  <div class="chart-card">
    <h3>Opérations du jour</h3>
    <p class="hint">Répartition des types d'opérations traitées aujourd'hui.</p>
    <div class="chart-wrap"><canvas id="chartJour"></canvas></div>
  </div>
</div>

<div class="section-title"><h2>Opérations du jour</h2><a href="{{ route('epargne.index') }}" class="btn btn-navy btn-sm">Nouvelle opération</a></div>
<div class="card">
  <table>
    <tr><th>Heure</th><th>Sociétaire</th><th>Type</th><th>Montant</th></tr>
    @forelse($operationsDuJour as $t)
      <tr>
        <td>{{ $t->date_operation->format('H:i') }}</td>
        <td>{{ $t->compteEpargne->societaire->nomComplet() ?? '—' }}</td>
        <td><span class="badge {{ $t->type === 'depot' ? 'b-green' : 'b-red' }}">{{ ucfirst($t->type) }}</span></td>
        <td>{{ number_format($t->montant,0,',',' ') }} F</td>
      </tr>
    @empty
      <tr><td colspan="4" style="color:#5c6479;">Aucune opération aujourd'hui.</td></tr>
    @endforelse
  </table>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var c1 = document.getElementById('chartFlux');
    if (c1) {
        new Chart(c1, {
            type: 'bar',
            data: {
                labels: @json($labels7Jours),
                datasets: [
                    { label: 'Dépôts', data: @json($serieDepots), backgroundColor: 'rgba(30,138,95,.85)', borderRadius: 8, maxBarThickness: 30 },
                    { label: 'Retraits', data: @json($serieRetraits), backgroundColor: 'rgba(196,69,59,.85)', borderRadius: 8, maxBarThickness: 30 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', boxWidth: 8, font: { family: "'Manrope', sans-serif", size: 11 } } } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#5c6479', font: { size: 11 } } },
                    y: { beginAtZero: true, grid: { color: '#e6e9f2' }, ticks: { color: '#5c6479', font: { size: 11 } } }
                }
            }
        });
    }

    var c2 = document.getElementById('chartJour');
    var types = @json($operationsDuJour->groupBy('type')->map(fn ($g, $t) => ['label' => $t === 'depot' ? 'Dépôts' : ($t === 'retrait' ? 'Retraits' : ucfirst($t)), 'total' => $g->count()])->values());
    var values = types.map(function (x) { return x.total; });
    if (c2 && values.some(function (v) { return v > 0; })) {
        new Chart(c2, {
            type: 'doughnut',
            data: {
                labels: types.map(function (x) { return x.label; }),
                datasets: [{ data: values, backgroundColor: ['#1e8a5f', '#c4453b', '#0a3a8f', '#e8a33d'], borderWidth: 2, borderColor: 'var(--surface)' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '62%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, boxWidth: 8, font: { family: "'Manrope', sans-serif", size: 11 } } } }
            }
        });
    } else if (c2) {
        c2.parentElement.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12.5px;">Aucune opération aujourd\'hui.</div>';
    }
});
</script>
@endpush
@endsection