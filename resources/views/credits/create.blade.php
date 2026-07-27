@extends('layouts.app')
@section('title', 'Nouvelle demande de crédit')
@section('content')
<div class="card card-pad" style="max-width:640px;">
  <form method="POST" action="{{ route('credits.store') }}">
    @csrf
    <div class="field">
      <label>Sociétaire</label>
      <select name="societaire_id" required>
        <option value="">— Sélectionner —</option>
        @foreach($societaires as $s)
          <option value="{{ $s->id }}">{{ $s->numero_societaire }} — {{ $s->nom }} {{ $s->prenom }}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label>Type de produit</label>
      <select name="type" id="type" required onchange="document.getElementById('sousTypeBlock').style.display = this.value === 'ordinaire' ? 'block' : 'none'; document.getElementById('partenaireBlock').style.display = this.value === 'partenariat' ? 'block' : 'none';">
        <option value="ordinaire">Crédit ordinaire</option>
        <option value="partenariat">Crédit de partenariat</option>
        <option value="tontine">Crédit tontine (adossé LOGOKU)</option>
      </select>
    </div>
    <div class="field" id="sousTypeBlock">
      <label>Sous-type (crédit ordinaire)</label>
      <select name="sous_type">
        @foreach($sousTypesOrdinaire as $st)
          <option value="{{ $st }}">{{ ucfirst(str_replace('_',' ',$st)) }}</option>
        @endforeach
      </select>
    </div>
    <div class="field" id="partenaireBlock" style="display:none;">
      <label>Partenaire</label><input name="partenaire">
    </div>
    <div class="grid g2">
      <div class="field"><label>Montant sollicité (F CFA)</label><input type="number" step="0.01" name="montant" required></div>
      <div class="field"><label>Durée (mois)</label><input type="number" name="duree_mois" value="12" required></div>
    </div>
    <div class="field"><label>Taux d'intérêt annuel (%)</label><input type="number" step="0.01" name="taux_interet" value="12" required></div>
    <button class="btn btn-navy" type="submit">Enregistrer la demande</button>
  </form>
</div>
@endsection
