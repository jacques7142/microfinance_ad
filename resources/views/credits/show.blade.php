@extends('layouts.app')
@section('title', 'Dossier crédit #'.$credit->id)
@section('content')
<div class="grid g4">
  <div class="card stat card-pad"><div class="lbl">Montant</div><div class="val">{{ number_format($credit->montant,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Statut</div><div class="val" style="font-size:16px;">{{ ucfirst(str_replace('_',' ',$credit->statut)) }}</div></div>
  <div class="card stat card-pad"><div class="lbl">TAEG estimé</div><div class="val">{{ $credit->taegApproximatif() }} %</div></div>
  <div class="card stat card-pad"><div class="lbl">Plafond légal</div><div class="val">{{ config('coopec.taux_usure_plafond') }} %</div></div>
</div>

<div class="section-title"><h2>Informations</h2></div>
<div class="card card-pad">
  <p><b>Sociétaire :</b> {{ $credit->societaire->nomComplet() }} ({{ $credit->societaire->numero_societaire }})</p>
  <p><b>Produit :</b> {{ $credit->libelleType() }}</p>
  <p><b>Durée :</b> {{ $credit->duree_mois }} mois — <b>Taux nominal :</b> {{ $credit->taux_interet }} %</p>
  @if($credit->avis_agent)<p><b>Avis de l'agent de crédit :</b> {{ $credit->avis_agent }}</p>@endif
</div>

@if(auth()->user()->role === 'agent_credit' && $credit->statut === 'recue')
<div class="section-title"><h2>Instruire le dossier</h2></div>
<div class="card card-pad">
  <form method="POST" action="{{ route('credits.instruire', $credit) }}">
    @csrf
    <div class="field"><label>Avis</label><textarea name="avis_agent" rows="3" required></textarea></div>
    <button class="btn btn-navy" type="submit">Transmettre au gérant</button>
  </form>
</div>
@endif

@if(auth()->user()->role === 'gerant' && $credit->statut === 'transmise_gerant')
<div class="section-title"><h2>Décision</h2></div>
<div class="card card-pad" style="display:flex;gap:10px;">
  <form method="POST" action="{{ route('credits.valider', $credit) }}">@csrf<button class="btn btn-navy" type="submit">Valider</button></form>
  <form method="POST" action="{{ route('credits.rejeter', $credit) }}" onsubmit="return confirm('Confirmer le rejet ?');">
    @csrf<input type="hidden" name="motif_rejet" value="Dossier ne répondant pas aux critères de l'agence.">
    <button class="btn btn-danger" type="submit">Rejeter</button>
  </form>
</div>
@endif

@if($credit->echeances->isNotEmpty())
<div class="section-title"><h2>Échéancier</h2></div>
<div class="card">
  <table>
    <tr><th>Échéance</th><th>Montant dû</th><th>Payé</th><th>Statut</th></tr>
    @foreach($credit->echeances as $e)
      <tr>
        <td>{{ $e->date_echeance->format('d/m/Y') }}</td>
        <td>{{ number_format($e->montant_du,0,',',' ') }} F</td>
        <td>{{ number_format($e->montant_paye,0,',',' ') }} F</td>
        <td><span class="badge {{ $e->statut === 'payee' ? 'b-green' : ($e->estEnRetard() ? 'b-red' : 'b-amber') }}">{{ ucfirst(str_replace('_',' ',$e->statut)) }}</span></td>
      </tr>
    @endforeach
  </table>
</div>
@endif
@endsection
