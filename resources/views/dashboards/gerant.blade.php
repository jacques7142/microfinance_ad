@extends('layouts.app')
@section('title', "Supervision d'agence")
@section('content')
<div class="grid g4">
  <div class="card stat card-pad"><div class="lbl">Portefeuille crédit validé</div><div class="val">{{ number_format($portefeuille,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Sociétaires de l'agence</div><div class="val">{{ $nbSocietaires }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Crédits ce mois</div><div class="val">{{ $nbCreditsMois }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Seuil de validation</div><div class="val">{{ number_format(auth()->user()->seuil_validation ?? 0,0,',',' ') }} F</div></div>
</div>

<div class="section-title"><h2>Accès rapide</h2></div>
<div class="grid" style="grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px;">
  <a href="{{ route('rapports.index') }}" class="card card-pad" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:all 0.2s;">
    <div style="width:44px;height:44px;border-radius:10px;background:var(--green-bg);display:flex;align-items:center;justify-content:center;flex:none;color:var(--green);"><x-icon name="chart" size="20"/></div>
    <div><div style="font-weight:700;font-size:13px;color:var(--ink);">Rapports</div><div style="font-size:11px;color:var(--muted);">Générer &amp; consulter</div></div>
  </a>
  <a href="{{ route('societaires.create') }}" class="card card-pad" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:all 0.2s;">
    <div style="width:44px;height:44px;border-radius:10px;background:var(--amber-bg);display:flex;align-items:center;justify-content:center;flex:none;color:var(--gold-2);"><x-icon name="plus" size="20"/></div>
    <div><div style="font-weight:700;font-size:13px;color:var(--ink);">Nouveau sociétaire</div><div style="font-size:11px;color:var(--muted);">Ajouter un membre</div></div>
  </a>
  <a href="{{ route('credits.create') }}" class="card card-pad" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:all 0.2s;">
    <div style="width:44px;height:44px;border-radius:10px;background:var(--green-bg);display:flex;align-items:center;justify-content:center;flex:none;color:var(--green);"><x-icon name="credit" size="20"/></div>
    <div><div style="font-weight:700;font-size:13px;color:var(--ink);">Nouveau crédit</div><div style="font-size:11px;color:var(--muted);">Saisir une demande</div></div>
  </a>
  <a href="{{ auth()->user()->agence_id ? route('agences.show', auth()->user()->agence_id) : '#' }}" class="card card-pad" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:all 0.2s;{{ auth()->user()->agence_id ? '' : 'opacity:.5;pointer-events:none;' }}">
    <div style="width:44px;height:44px;border-radius:10px;background:#e8edfb;display:flex;align-items:center;justify-content:center;flex:none;color:var(--navy-2);"><x-icon name="building" size="20"/></div>
    <div><div style="font-weight:700;font-size:13px;color:var(--ink);">Mon agence</div><div style="font-size:11px;color:var(--muted);">Voir les détails</div></div>
  </a>
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
@endsection