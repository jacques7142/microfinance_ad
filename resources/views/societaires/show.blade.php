@extends('layouts.app')
@section('title', $societaire->nomComplet())
@section('content')
<div class="grid g4">
  <div class="card stat card-pad"><div class="lbl">Part sociale</div><div class="val">{{ number_format($societaire->part_sociale,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Épargne totale</div><div class="val">{{ number_format($societaire->soldeTotalEpargne(),0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Crédits en cours</div><div class="val">{{ $societaire->credits->where('statut','validee')->count() }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Statut</div><div class="val" style="font-size:16px;">{{ ucfirst($societaire->statut) }}</div></div>
</div>

<div class="section-title"><h2>Comptes d'épargne</h2></div>
<div class="card">
  <table>
    <tr><th>Type</th><th>Solde</th><th>Ouvert le</th></tr>
    @forelse($societaire->comptesEpargne as $c)
      <tr><td>{{ $c->type }}</td><td>{{ number_format($c->solde,0,',',' ') }} F</td><td>{{ $c->date_ouverture->format('d/m/Y') }}</td></tr>
    @empty
      <tr><td colspan="3" style="color:#5c6479;">Aucun compte d'épargne.</td></tr>
    @endforelse
  </table>
</div>

@if($societaire->compteTontine)
<div class="section-title"><h2>Compte tontine LOGOKU</h2></div>
<div class="card card-pad">
  <p>Solde accumulé : <b>{{ number_format($societaire->compteTontine->solde_accumule,0,',',' ') }} F</b> — Plafond crédit adossé : <b>{{ number_format($societaire->compteTontine->plafondCreditAdosse(),0,',',' ') }} F</b></p>
</div>
@endif

<div class="section-title"><h2>Crédits</h2></div>
<div class="card">
  <table>
    <tr><th>Type</th><th>Montant</th><th>Statut</th><th></th></tr>
    @forelse($societaire->credits as $credit)
      <tr>
        <td>{{ $credit->libelleType() }}</td>
        <td>{{ number_format($credit->montant,0,',',' ') }} F</td>
        <td><span class="badge b-navy">{{ ucfirst(str_replace('_',' ',$credit->statut)) }}</span></td>
        <td><a href="{{ route('credits.show', $credit) }}" class="btn btn-ghost btn-sm"><x-icon name="eye" size="14"/> Ouvrir</a></td>
      </tr>
    @empty
      <tr><td colspan="4" style="color:#5c6479;">Aucun crédit.</td></tr>
    @endforelse
  </table>
</div>
@endsection
