@extends('layouts.app')
@section('title', 'Nouveau sociétaire')
@section('content')
<div class="card card-pad" style="max-width:640px;">
  <form method="POST" action="{{ route('societaires.store') }}">
    @csrf
    <div class="grid g2">
      <div class="field"><label>Nom</label><input name="nom" value="{{ old('nom') }}" required></div>
      <div class="field"><label>Prénom</label><input name="prenom" value="{{ old('prenom') }}" required></div>
    </div>
    <div class="grid g2">
      <div class="field"><label>Téléphone</label><input name="telephone" value="{{ old('telephone') }}" required></div>
      <div class="field"><label>Adresse</label><input name="adresse" value="{{ old('adresse') }}"></div>
    </div>
    <div class="grid g2">
      <div class="field"><label>Part sociale (F CFA)</label><input type="number" step="0.01" name="part_sociale" value="5000" required></div>
      <div class="field"><label>Droit d'adhésion (F CFA)</label><input type="number" step="0.01" name="droit_adhesion" value="2000" required></div>
    </div>
    <button class="btn btn-navy" type="submit"><x-icon name="save" size="16"/> Enregistrer le sociétaire</button>
  </form>
</div>
@endsection
