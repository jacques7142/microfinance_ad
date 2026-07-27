@extends('layouts.app')
@section('title', 'Guichet — Caisse')
@section('content')
<div class="grid g3">
  <div class="card stat card-pad"><div class="lbl">Dépôts du jour</div><div class="val">{{ number_format($totalDepots,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Retraits du jour</div><div class="val">{{ number_format($totalRetraits,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Opérations traitées</div><div class="val">{{ $operationsDuJour->count() }}</div></div>
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
@endsection
