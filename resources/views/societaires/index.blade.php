@extends('layouts.app')
@section('title', 'Sociétaires')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
  <form method="GET" style="display:flex;gap:8px;">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, prénom ou numéro" style="padding:9px 12px;border-radius:8px;border:1px solid #e6e9f2;min-width:260px;">
    <button class="btn btn-ghost" type="submit">Rechercher</button>
  </form>
  @if(in_array(auth()->user()->role, ['agent_credit','gerant','administrateur']))
    <a href="{{ route('societaires.create') }}" class="btn btn-navy">+ Nouveau sociétaire</a>
  @endif
</div>

<div class="card">
  <table>
    <tr><th>N° sociétaire</th><th>Nom</th><th>Téléphone</th><th>Agence</th><th>Statut</th><th></th></tr>
    @forelse($societaires as $s)
      <tr>
        <td>{{ $s->numero_societaire }}</td>
        <td>{{ $s->nomComplet() }}</td>
        <td>{{ $s->telephone }}</td>
        <td>{{ $s->agence->nom }}</td>
        <td><span class="badge {{ $s->statut === 'actif' ? 'b-green' : 'b-amber' }}">{{ ucfirst($s->statut) }}</span></td>
        <td><a href="{{ route('societaires.show', $s) }}" class="btn btn-ghost btn-sm">Ouvrir</a></td>
      </tr>
    @empty
      <tr><td colspan="6" style="color:#5c6479;">Aucun sociétaire trouvé.</td></tr>
    @endforelse
  </table>
</div>
<div style="margin-top:16px;">{{ $societaires->links() }}</div>
@endsection
