@extends('layouts.app')
@section('title', 'Comptabilité & reporting')
@section('content')
<div class="grid g3">
  <div class="card stat card-pad"><div class="lbl">Total actif épargne</div><div class="val">{{ number_format($totalEpargne,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Encours de crédit validé</div><div class="val">{{ number_format($encoursCredit,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Plafond TAEG en vigueur</div><div class="val">{{ config('coopec.taux_usure_plafond') }} %</div></div>
</div>
<div class="section-title"><h2>Répartition des crédits par type</h2><a href="{{ route('rapports.index') }}" class="btn btn-navy btn-sm">Aller aux rapports</a></div>
<div class="card">
  <table>
    <tr><th>Type</th><th>Nombre</th></tr>
    @foreach($creditsParType as $row)
      <tr><td>{{ ucfirst($row->type) }}</td><td>{{ $row->total }}</td></tr>
    @endforeach
  </table>
</div>
@endsection
