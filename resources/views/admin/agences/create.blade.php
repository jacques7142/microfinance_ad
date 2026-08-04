@extends('layouts.app')
@section('title', 'Nouvelle agence')
@section('content')
<div class="card card-pad" style="max-width:560px;">
  <form method="POST" action="{{ route('admin.agences.store') }}">
    @csrf
    <div class="field"><label>Nom</label><input name="nom" required></div>
    <div class="field"><label>Ville</label><input name="ville" required></div>
    <div class="field"><label>Adresse</label><input name="adresse"></div>
    <div class="field"><label>Date d'ouverture</label><input type="date" name="date_ouverture"></div>
    <div class="field"><label><input type="checkbox" name="est_siege" value="1" style="width:auto;"> Cette agence est le siège (Direction Générale)</label></div>
    <button class="btn btn-navy" type="submit"><x-icon name="plus" size="16"/> Créer l'agence</button>
  </form>
</div>
@endsection
