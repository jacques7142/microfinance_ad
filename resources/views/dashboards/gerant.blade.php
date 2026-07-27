@extends('layouts.app')
@section('title', "Supervision d'agence")
@section('content')
<div class="grid g3">
  <div class="card stat card-pad"><div class="lbl">Portefeuille crédit validé</div><div class="val">{{ number_format($portefeuille,0,',',' ') }} F</div></div>
  <div class="card stat card-pad"><div class="lbl">Sociétaires de l'agence</div><div class="val">{{ $nbSocietaires }}</div></div>
  <div class="card stat card-pad"><div class="lbl">Seuil de validation</div><div class="val">{{ number_format(auth()->user()->seuil_validation ?? 0,0,',',' ') }} F</div></div>
</div>

<div class="section-title"><h2>Demandes de crédit à valider</h2></div>
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
            <button class="btn btn-navy btn-sm" type="submit">Valider</button>
          </form>
          <form method="POST" action="{{ route('credits.rejeter', $credit) }}" style="display:inline;" onsubmit="return confirm('Confirmer le rejet ?');">
            @csrf
            <input type="hidden" name="motif_rejet" value="Dossier ne répondant pas aux critères de l'agence.">
            <button class="btn btn-danger btn-sm" type="submit">Rejeter</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" style="color:#5c6479;">Aucune demande en attente de validation.</td></tr>
    @endforelse
  </table>
</div>
@endsection
