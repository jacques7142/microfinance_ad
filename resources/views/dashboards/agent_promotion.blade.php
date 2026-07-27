@extends('layouts.app')
@section('title', 'Agent de promotion — Tontine LOGOKU')
@section('content')
<div class="grid g3">
  <div class="card stat card-pad"><div class="lbl">Collecté aujourd'hui</div><div class="val">{{ number_format($totalCollecte,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Passages effectués</div><div class="val">{{ $collectesDuJour->count() }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Zone de tournée</div><div class="val" style="font-size:16px;">{{ auth()->user()->zone_tournee ?? '—' }}</div></div>
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
@endsection
