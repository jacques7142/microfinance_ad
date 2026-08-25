@extends('layouts.app')
@section('title', 'Comptabilité & reporting')
@section('content')

<style>
.chart-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.chart-card h3{font-size:14px;margin:0 0 4px;}
.chart-card .hint{font-size:11.5px;color:var(--muted);margin:0 0 14px;}
.chart-wrap{position:relative;height:240px;}
.charts-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:26px;}
@media(max-width:900px){.charts-grid{grid-template-columns:1fr;}}
</style>

<div class="grid g3">
  <div class="card stat card-pad"><div class="lbl">Total actif épargne</div><div class="val">{{ number_format($totalEpargne,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Encours de crédit validé</div><div class="val">{{ number_format($encoursCredit,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Plafond TAEG en vigueur</div><div class="val">{{ config('coopec.taux_usure_plafond') }} %</div></div>
</div>

<div class="charts-grid">
  <div class="chart-card">
    <h3>Répartition de l'épargne</h3>
    <p class="hint">Encours par type de compte (DAV / DAT) de l'agence.</p>
    <div class="chart-wrap"><canvas id="chartEpargne"></canvas></div>
  </div>
  <div class="chart-card">
    <h3>Crédits par type</h3>
    <p class="hint">Répartition des demandes de crédit de l'agence.</p>
    <div class="chart-wrap"><canvas id="chartCredits"></canvas></div>
  </div>
</div>

<div class="section-title"><h2>Détail des crédits par type</h2><a href="{{ route('rapports.index') }}" class="btn btn-navy btn-sm">Aller aux rapports</a></div>
<div class="card">
  <table>
    <tr><th>Type</th><th>Nombre</th><th>Répartition</th></tr>
    @php $totalCredits = $creditsParType->sum('total'); @endphp
    @forelse($creditsParType as $row)
      <tr>
        <td>{{ $row['label'] }}</td>
        <td>{{ $row['total'] }}</td>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="progress-track" style="flex:1;max-width:220px;height:7px;border-radius:99px;background:var(--line);overflow:hidden;">
              <div style="height:100%;width:{{ $totalCredits > 0 ? round($row['total'] / $totalCredits * 100) : 0 }}%;border-radius:99px;background:linear-gradient(90deg,var(--gold),var(--gold-2));"></div>
            </div>
            <span style="font-size:11.5px;color:var(--muted);">{{ $totalCredits > 0 ? round($row['total'] / $totalCredits * 100) : 0 }} %</span>
          </div>
        </td>
      </tr>
    @empty
      <tr><td colspan="3" style="color:var(--muted);">Aucun crédit enregistré.</td></tr>
    @endforelse
  </table>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function donut(id, data, colors) {
        var el = document.getElementById(id);
        var values = data.map(function (x) { return x.total; });
        if (!el || !values.some(function (v) { return v > 0; })) {
            if (el) { el.parentElement.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12.5px;">Aucune donnée.</div>'; }
            return;
        }
        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: data.map(function (x) { return x.label; }),
                datasets: [{ data: values, backgroundColor: colors, borderWidth: 2, borderColor: 'var(--surface)' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, boxWidth: 8, font: { family: "'Manrope', sans-serif", size: 11 } } },
                    tooltip: { callbacks: { label: function (ctx) { var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0); var pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0; return ' ' + ctx.label + ' : ' + ctx.formattedValue + ' (' + pct + '%)'; } } }
                }
            }
        });
    }
    donut('chartEpargne', @json($epargneParType), ['#0a3a8f', '#e8a33d', '#1e8a5f']);
    donut('chartCredits', @json($creditsParType), ['#011f62', '#7c3aed', '#1e8a5f', '#c97f1e']);
});
</script>
@endpush
@endsection