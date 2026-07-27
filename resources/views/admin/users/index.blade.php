@extends('layouts.app')
@section('title', 'Utilisateurs internes')
@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:14px;">
  <a href="{{ route('admin.users.create') }}" class="btn btn-navy">+ Nouvel utilisateur</a>
</div>
<div class="card">
  <table>
    <tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Agence</th><th>Statut</th><th></th></tr>
    @foreach($utilisateurs as $u)
      <tr>
        <td>{{ $u->nomComplet() }}</td>
        <td>{{ $u->email }}</td>
        <td><span class="badge b-navy">{{ ucfirst(str_replace('_',' ',$u->role)) }}</span></td>
        <td>{{ $u->agence->nom ?? '—' }}</td>
        <td><span class="badge {{ $u->actif ? 'b-green' : 'b-red' }}">{{ $u->actif ? 'Actif' : 'Désactivé' }}</span></td>
        <td>
          <form method="POST" action="{{ route('admin.users.toggle-actif', $u) }}">
            @csrf
            <button class="btn btn-ghost btn-sm" type="submit">{{ $u->actif ? 'Désactiver' : 'Réactiver' }}</button>
          </form>
        </td>
      </tr>
    @endforeach
  </table>
</div>
<div style="margin-top:16px;">{{ $utilisateurs->links() }}</div>
@endsection
