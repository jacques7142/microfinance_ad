@extends('layouts.app')
@section('title', $rapport->type_rapport)
@section('content')
<div class="card card-pad" style="max-width:640px;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h3 style="font-size:16px;">{{ $rapport->type_rapport }}</h3>
    <span class="badge b-navy">{{ strtoupper($rapport->format_export) }}</span>
  </div>
  <table style="width:100%;">
    <tr><td style="font-weight:700;width:140px;">Période</td><td>{{ $rapport->periode }}</td></tr>
    <tr><td style="font-weight:700;">Date génération</td><td>{{ $rapport->date_generation->format('d/m/Y H:i') }}</td></tr>
    <tr><td style="font-weight:700;">Portée</td><td>{{ $rapport->estMultiAgences() ? 'Consolidé (toutes agences)' : $rapport->agence?->nom ?? '—' }}</td></tr>
    <tr><td style="font-weight:700;">Généré par</td><td>{{ $rapport->utilisateur?->nomComplet() ?? '—' }}</td></tr>
  </table>
  <div style="margin-top:24px;display:flex;gap:10px;">
    <a href="{{ route('rapports.edit', $rapport) }}" class="btn btn-navy"><x-icon name="edit" size="16"/> Modifier</a>
    <form method="POST" action="{{ route('rapports.destroy', $rapport) }}" onsubmit="return confirm('Supprimer ce rapport ?');" style="display:inline;">
      @csrf @method('DELETE')
      <button class="btn btn-danger" type="submit"><x-icon name="x" size="16"/> Supprimer</button>
    </form>
    <a href="{{ route('rapports.index') }}" class="btn btn-ghost">Retour</a>
  </div>
</div>
@endsection