@extends('layouts.app')
@section('title', 'Agent de crédit')
@section('content')

<style>
.chart-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.chart-card h3{font-size:14px;margin:0 0 4px;}
.chart-card .hint{font-size:11.5px;color:var(--muted);margin:0 0 14px;}
.chart-wrap{position:relative;height:260px;}
.stats-top{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:22px;}
@media(max-width:760px){.stats-top{grid-template-columns:1fr;}}
</style>

<div class="stats-top">
  <div class="card stat card-pad"><div class="lbl">Dossiers au total</div><div class="val">{{ $totalDossiers }}</div></div>
  <div class="card stat card-pad"><div class="lbl">En attente de validation</div><div class="val">{{ ($creditsParStatut['transmise_gerant'] ?? collect())->count() }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Validés</div><div class="val" style="color:var(--green);">{{ ($creditsParStatut['validee'] ?? collect())->count() }}</div></div>
</div>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
  <h2 style="font-size:17px;margin:0;">Pipeline de vos dossiers</h2>
  <div style="display:flex;gap:8px;">
    <a href="{{ route('societaires.create') }}" class="btn btn-ghost"><x-icon name="plus" size="16"/> Nouveau sociétaire</a>
    <a href="{{ route('credits.create') }}" class="btn btn-navy"><x-icon name="plus" size="16"/> Nouvelle demande</a>
  </div>
</div>

<div class="chart-card" style="margin-bottom:24px;">
  <h3>Répartition par statut</h3>
  <p class="hint">Vue d'ensemble du cheminement de vos demandes de crédit.</p>
  <div class="chart-wrap"><canvas id="chartPipeline"></canvas></div>
</div>

@foreach(['recue'=>'Reçues','en_instruction'=>'En instruction','transmise_gerant'=>'Transmises au gérant','validee'=>'Validées','rejetee'=>'Rejetées'] as $statut => $label)
  <div class="section-title"><h2>{{ $label }} ({{ ($creditsParStatut[$statut] ?? collect())->count() }})</h2></div>
  <div class="card" style="margin-bottom:8px;">
    <table>
      <tr><th>Sociétaire</th><th>Produit</th><th>Montant</th><th>Date</th><th></th></tr>
      @forelse(($creditsParStatut[$statut] ?? collect()) as $credit)
        <tr>
          <td>{{ $credit->societaire->nomComplet() }}</td>
          <td>{{ $credit->libelleType() }}</td>
          <td>{{ number_format($credit->montant,0,',',' ') }} F</td>
          <td>{{ $credit->date_demande->format('d/m/Y') }}</td>
          <td><a href="{{ route('credits.show', $credit) }}" class="btn btn-ghost btn-sm"><x-icon name="eye" size="14"/> Ouvrir</a></td>
        </tr>
      @empty
        <tr><td colspan="5" style="color:#5c6479;">Aucun dossier.</td></tr>
      @endforelse
    </table>
  </div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('chartPipeline');
    var values = @json($pipeline->pluck('total'));
    if (el && values.some(function (v) { return v > 0; })) {
        var P = ['#0a3a8f', '#3b82f6', '#e8a33d', '#1e8a5f', '#c4453b'];
        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: @json($pipeline->pluck('label')),
                datasets: [{ data: values, backgroundColor: P, borderWidth: 2, borderColor: 'var(--surface)' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, boxWidth: 8, font: { family: "'Manrope', sans-serif", size: 11 } } },
                    tooltip: { callbacks: { label: function (ctx) { var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0); return ' ' + ctx.label + ' : ' + ctx.formattedValue + ' (' + (total > 0 ? Math.round(ctx.parsed / total * 100) : 0) + '%)'; } } }
                }
            }
        });
    } else if (el) {
        el.parentElement.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12.5px;">Aucun dossier dans le pipeline.</div>';
    }
});
</script>
@endpush
@endsection