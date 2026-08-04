@extends('layouts.app')
@section('title', 'Agent de crédit')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;">
  <p style="color:#5c6479;font-size:13px;">Pipeline de vos dossiers de crédit, par statut.</p>
  <div style="display:flex;gap:8px;">
    <a href="{{ route('societaires.create') }}" class="btn btn-ghost"><x-icon name="plus" size="16"/> Nouveau sociétaire</a>
    <a href="{{ route('credits.create') }}" class="btn btn-navy"><x-icon name="plus" size="16"/> Nouvelle demande</a>
  </div>
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
@endsection
