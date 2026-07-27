@extends('layouts.app')
@section('title', "Réseau d'agences")
@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:14px;">
  <a href="{{ route('admin.agences.create') }}" class="btn btn-navy">+ Nouvelle agence</a>
</div>
<div class="card">
  <table>
    <tr><th>Nom</th><th>Ville</th><th>Sociétaires</th><th>Utilisateurs</th><th>Type</th></tr>
    @foreach($agences as $a)
      <tr>
        <td>{{ $a->nom }}</td>
        <td>{{ $a->ville }}</td>
        <td>{{ $a->societaires_count }}</td>
        <td>{{ $a->utilisateurs_count }}</td>
        <td>{{ $a->est_siege ? 'Direction Générale' : 'Agence' }}</td>
      </tr>
    @endforeach
  </table>
</div>
@endsection
