@extends('layouts.app')
@section('title', "Supervision d'agence")
@section('content')

<style>
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;}
.charts-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;}
@media(max-width:1000px){.charts-grid{grid-template-columns:1fr;}}
@media(max-width:760px){.stats-grid{grid-template-columns:repeat(2,1fr);}}
.chart-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
.chart-card h3{font-size:14px;margin:0 0 4px;}
.chart-card .hint{font-size:11.5px;color:var(--muted);margin:0 0 14px;}
.chart-wrap{position:relative;height:230px;}
.chart-center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;}
.chart-center b{font-family:'Sora';font-size:20px;color:var(--navy);}
.chart-center span{font-size:11px;color:var(--muted);}
.service-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:16px;}
@media(max-width:760px){.service-kpis{grid-template-columns:repeat(2,1fr);}}
.equipe-row{display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--line);}
.equipe-row:last-child{border-bottom:none;}
.equipe-row:hover{background:var(--bg);}
.equipe-avatar{width:38px;height:38px;border-radius:50%;background:var(--navy-2);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-family:'Sora';font-size:12px;flex:none;}
.progress-track{height:6px;border-radius:99px;background:var(--line);overflow:hidden;flex:1;min-width:80px;}
.progress-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--gold),var(--gold-2));}
.role-tag{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--muted);white-space:nowrap;}
.quick{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:26px;}
@media(max-width:760px){.quick{grid-template-columns:repeat(2,1fr);}}
</style>

<div class="grid g4">
  <div class="card stat card-pad"><div class="lbl">Portefeuille crédit validé</div><div class="val">{{ number_format($portefeuille,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Sociétaires de l'agence</div><div class="val">{{ $nbSocietaires }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Crédits ce mois</div><div class="val">{{ $nbCreditsMois }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Seuil de validation</div><div class="val">{{ number_format(auth()->user()->seuil_validation ?? 0,0,',',' ') }} F</div></div>
</div>

<div class="section-title"><h2>Accès rapide</h2></div>
<div class="quick">
  <a href="{{ route('rapports.index') }}" class="card card-pad" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:all .2s;">
    <div style="width:44px;height:44px;border-radius:10px;background:var(--green-bg);display:flex;align-items:center;justify-content:center;flex:none;color:var(--green);"><x-icon name="chart" size="20"/></div>
    <div><div style="font-weight:700;font-size:13px;color:var(--ink);">Rapports</div><div style="font-size:11px;color:var(--muted);">Générer &amp; consulter</div></div>
  </a>
  <a href="{{ route('societaires.create') }}" class="card card-pad" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:all .2s;">
    <div style="width:44px;height:44px;border-radius:10px;background:var(--amber-bg);display:flex;align-items:center;justify-content:center;flex:none;color:var(--gold-2);"><x-icon name="plus" size="20"/></div>
    <div><div style="font-weight:700;font-size:13px;color:var(--ink);">Nouveau sociétaire</div><div style="font-size:11px;color:var(--muted);">Ajouter un membre</div></div>
  </a>
  <a href="{{ route('credits.create') }}" class="card card-pad" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:all .2s;">
    <div style="width:44px;height:44px;border-radius:10px;background:var(--green-bg);display:flex;align-items:center;justify-content:center;flex:none;color:var(--green);"><x-icon name="credit" size="20"/></div>
    <div><div style="font-weight:700;font-size:13px;color:var(--ink);">Nouveau crédit</div><div style="font-size:11px;color:var(--muted);">Saisir une demande</div></div>
  </a>
  <a href="{{ auth()->user()->agence_id ? route('agences.show', auth()->user()->agence_id) : '#' }}" class="card card-pad" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:all .2s;{{ auth()->user()->agence_id ? '' : 'opacity:.5;pointer-events:none;' }}">
    <div style="width:44px;height:44px;border-radius:10px;background:#e8edfb;display:flex;align-items:center;justify-content:center;flex:none;color:var(--navy-2);"><x-icon name="building" size="20"/></div>
    <div><div style="font-weight:700;font-size:13px;color:var(--ink);">Mon agence</div><div style="font-size:11px;color:var(--muted);">Voir les détails</div></div>
  </a>
</div>

<div class="section-title"><h2>Statistiques de l'agence</h2></div>
<div class="charts-grid">
  <div class="chart-card">
    <h3>Crédits par statut</h3>
    <p class="hint">Suivi du workflow des demandes de crédit de l'agence.</p>
    <div class="chart-wrap"><canvas id="chartCreditStatut"></canvas></div>
  </div>
  <div class="chart-card">
    <h3>Types de crédits</h3>
    <p class="hint">Répartition des demandes par produit de financement.</p>
    <div class="chart-wrap"><canvas id="chartCreditType"></canvas></div>
  </div>
  <div class="chart-card">
    <h3>Sociétaires par statut</h3>
    <p class="hint">Composition du portefeuille membres de l'agence.</p>
    <div class="chart-wrap"><canvas id="chartSocietaires"></canvas></div>
  </div>
  <div class="chart-card">
    <h3>Activité mensuelle</h3>
    <p class="hint">Montants traités ce mois (dépôts, retraits, remboursements).</p>
    <div class="chart-wrap"><canvas id="chartActivite"></canvas></div>
  </div>
</div>

<div class="section-title"><h2>Suivi des services de l'agence</h2></div>
<div class="card card-pad" style="margin-bottom:16px;">
  <div class="service-kpis">
    <div class="stat card-pad" style="padding:0;"><div class="lbl">Épargne totale</div><div class="val" style="font-size:18px;">{{ number_format($totalEpargne,0,',',' ') }} F</div></div>
    <div class="stat card-pad" style="padding:0;"><div class="lbl">Collectes tontine (mois)</div><div class="val" style="font-size:18px;">{{ number_format($collectesMois,0,',',' ') }} F</div></div>
    <div class="stat card-pad" style="padding:0;"><div class="lbl">Opérations (mois)</div><div class="val" style="font-size:18px;">{{ $operationsMois }}</div></div>
    <div class="stat card-pad" style="padding:0;"><div class="lbl">Crédits à valider</div><div class="val" style="font-size:18px;">{{ $nbCreditsEnAttente }}</div></div>
  </div>
</div>

<div class="card">
  <div style="padding:15px 20px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;">
    <h3 style="font-size:14px;margin:0;">Activité des collaborateurs de l'agence</h3>
    <a href="{{ route('societaires.index') }}" class="btn btn-ghost btn-sm"><x-icon name="users" size="14"/> Voir les sociétaires</a>
  </div>
  <div style="padding:6px 0;">
    @forelse($equipe as $membre)
      @php
        $totalTaches = $membre->credits_instruits_count + $membre->collectes_enregistrees_count + $membre->transactions_count + $membre->rapports_count;
        $maxTaches = max(1, $equipe->max(fn ($m) => $m->credits_instruits_count + $m->collectes_enregistrees_count + $m->transactions_count + $m->rapports_count));
      @endphp
      <div class="equipe-row">
        <div class="equipe-avatar">{{ strtoupper(substr($membre->prenom,0,1)) }}{{ strtoupper(substr($membre->nom,0,1)) }}</div>
        <div style="flex:1;min-width:0;">
          <div style="font-weight:700;font-size:13px;">{{ $membre->nomComplet() }}</div>
          <div class="role-tag">{{ ucfirst(str_replace('_',' ',$membre->role)) }}</div>
        </div>
        <div style="display:flex;gap:14px;font-size:11.5px;color:var(--muted);white-space:nowrap;">
          <span title="Crédits instruits"><x-icon name="credit" size="12"/> {{ $membre->credits_instruits_count }}</span>
          <span title="Collectes tontine"><x-icon name="tontine" size="12"/> {{ $membre->collectes_enregistrees_count }}</span>
          <span title="Opérations"><x-icon name="wallet" size="12"/> {{ $membre->transactions_count }}</span>
          <span title="Rapports"><x-icon name="chart" size="12"/> {{ $membre->rapports_count }}</span>
        </div>
        <div class="progress-track" title="{{ $totalTaches }} actions réalisées"><div class="progress-fill" style="width:{{ round($totalTaches / $maxTaches * 100) }}%"></div></div>
      </div>
    @empty
      <div style="padding:24px;text-align:center;color:var(--muted);font-size:13px;">Aucun collaborateur rattaché à cette agence.</div>
    @endforelse
  </div>
</div>

<div class="section-title"><h2>Demandes de crédit à valider @if($nbCreditsEnAttente > 0)<span class="badge b-red" style="margin-left:8px;">{{ $nbCreditsEnAttente }}</span>@endif</h2></div>
<div class="card">
  <table>
    <tr><th>Sociétaire</th><th>Produit</th><th>Montant</th><th>Avis agent</th><th></th></tr>
    @forelse($creditsAValider as $credit)
      <tr>
        <td>{{ $credit->societaire->nomComplet() }}</td>
        <td>{{ $credit->libelleType() }}</td>
        <td>{{ number_format($credit->montant,0,',',' ') }} F</td>
        <td>{{ \Illuminate\Support\Str::limit($credit->avis_agent, 40) }}</td>
        <td>
          <form method="POST" action="{{ route('credits.valider', $credit) }}" style="display:inline;">@csrf
            <button class="btn btn-navy btn-sm" type="submit"><x-icon name="check" size="14"/> Valider</button>
          </form>
          <form method="POST" action="{{ route('credits.rejeter', $credit) }}" style="display:inline;" onsubmit="return confirm('Confirmer le rejet ?');">
            @csrf
            <input type="hidden" name="motif_rejet" value="Dossier ne répondant pas aux critères de l'agence.">
            <button class="btn btn-danger btn-sm" type="submit"><x-icon name="x" size="14"/> Rejeter</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" style="color:#5c6479;">Aucune demande en attente de validation.</td></tr>
    @endforelse
  </table>
</div>

@if($derniersRapports->isNotEmpty())
  <div class="section-title"><h2>Derniers rapports</h2></div>
  <div class="card">
    <table>
      <tr><th>Type</th><th>Période</th><th>Format</th><th>Date</th><th></th></tr>
      @foreach($derniersRapports as $r)
        <tr>
          <td>{{ $r->type_rapport }}</td>
          <td>{{ $r->periode }}</td>
          <td><span class="badge b-navy">{{ strtoupper($r->format_export) }}</span></td>
          <td>{{ $r->date_generation->format('d/m/Y') }}</td>
          <td><a href="{{ route('rapports.show', $r) }}" class="btn btn-sm btn-ghost"><x-icon name="eye" size="13"/> Voir</a></td>
        </tr>
      @endforeach
    </table>
  </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var P = [
        '#011f62', '#0a3a8f', '#e8a33d', '#1e8a5f', '#c4453b',
        '#7c3aed', '#0e7490', '#c97f1e', '#3b82f6', '#16a085',
        '#f06020', '#FFD200', '#6b7280', '#be185d'
    ];
    var chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '62%',
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, boxWidth: 8, font: { family: "'Manrope', sans-serif", size: 11 } } },
            tooltip: { callbacks: { label: function (ctx) { var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0); var pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0; return ' ' + ctx.label + ' : ' + ctx.formattedValue + ' (' + pct + '%)'; } } }
        }
    };
    function donut(id, labels, values) {
        var el = document.getElementById(id);
        if (!el || !values.length || values.every(function (v) { return v === 0; })) {
            if (el) { el.parentElement.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12.5px;">Aucune donnée.</div>'; }
            return;
        }
        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{ data: values, backgroundColor: values.map(function (_, i) { return P[i % P.length]; }), borderWidth: 2, borderColor: 'var(--surface)' }]
            },
            options: chartOptions
        });
    }
    donut('chartCreditStatut', @json($statutsCredit->pluck('label')), @json($statutsCredit->pluck('total')));
    donut('chartCreditType', @json($typesCredit->pluck('label')), @json($typesCredit->pluck('total')));
    donut('chartSocietaires', @json($societairesParStatut->pluck('label')), @json($societairesParStatut->pluck('total')));
    donut('chartActivite', @json($activiteMensuelle->pluck('label')), @json($activiteMensuelle->pluck('total')));
});
</script>
@endpush
@endsection