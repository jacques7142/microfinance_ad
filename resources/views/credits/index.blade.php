@extends('layouts.app')
@section('title', 'Crédits')
@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:14px;">
  @if(in_array(auth()->user()->role, ['agent_credit','gerant','administrateur']))
    <a href="{{ route('credits.create') }}" class="btn btn-navy">+ Nouvelle demande</a>
  @endif
</div>
<div class="card">
  <table>
    <tr><th>Sociétaire</th><th>Produit</th><th>Montant</th><th>Statut</th><th></th></tr>
    @forelse($credits as $credit)
      <tr>
        <td>{{ $credit->societaire->nomComplet() }}</td>
        <td>{{ $credit->libelleType() }}</td>
        <td>{{ number_format($credit->montant,0,',',' ') }} F</td>
        <td><span class="badge b-navy">{{ ucfirst(str_replace('_',' ',$credit->statut)) }}</span></td>
        <td><a href="{{ route('credits.show', $credit) }}" class="btn btn-ghost btn-sm">Ouvrir</a></td>
      </tr>
    @empty
      <tr><td colspan="5" style="color:#5c6479;">Aucun crédit.</td></tr>
    @endforelse
  </table>
</div>
<div style="margin-top:16px;">{{ $credits->links() }}</div>
@endsection
