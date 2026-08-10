@extends('layouts.app')
@section('title', 'Utilisateurs internes')
@section('content')
<div style="display:flex;justify-content:flex-end;margin-bottom:14px;">
  <a href="{{ route('admin.users.create') }}" class="btn btn-navy"><x-icon name="plus" size="16"/> Nouvel utilisateur</a>
</div>
<div class="card">
  <table>
    <tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Agence</th><th>Statut</th><th>Accès rapides</th></tr>
    @foreach($utilisateurs as $u)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="width:10px;height:10px;border-radius:50%;background:{{ $u->couleur ?? '#011f62' }};display:inline-block;flex:none;"></span>
            <x-icon name="profile" size="14"/> {{ $u->nomComplet() }}
          </div>
        </td>
        <td>{{ $u->email }}</td>
        <td>
          @foreach($u->rolesAttribues() as $role)
            <span class="badge b-navy" style="margin-right:4px;">{{ ucfirst(str_replace('_',' ',$role)) }}</span>
          @endforeach
        </td>
        <td>{{ $u->agence->nom ?? '—' }}</td>
        <td><span class="badge {{ $u->actif ? 'b-green' : 'b-red' }}">{{ $u->actif ? 'Actif' : 'Désactivé' }}</span></td>
        <td>
          <div style="display:flex;align-items:center;gap:6px;">
            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-ghost btn-sm" title="Modifier">
              <x-icon name="edit" size="14"/>
            </a>
            <form method="POST" action="{{ route('admin.users.couleur', $u) }}" style="display:inline;" class="color-form">
              @csrf
              <input type="color" name="couleur" value="{{ $u->couleur ?? '#011f62' }}"
                     onchange="this.closest('form').submit()"
                     style="width:28px;height:28px;padding:1px;border-radius:6px;border:1px solid var(--line);cursor:pointer;background:none;"
                     title="Attribuer une couleur">
            </form>
            <form method="POST" action="{{ route('admin.users.toggle-actif', $u) }}" style="display:inline;">
              @csrf
              <button class="btn btn-ghost btn-sm" type="submit" title="{{ $u->actif ? 'Désactiver' : 'Réactiver' }}">
                <x-icon name="{{ $u->actif ? 'x' : 'check' }}" size="14"/>
              </button>
            </form>
            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline;"
                  onsubmit="return confirm('Supprimer définitivement {{ $u->prenom }} {{ $u->nom }} ?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-danger btn-sm" type="submit" title="Supprimer">
                <x-icon name="trash" size="14"/>
              </button>
            </form>
          </div>
        </td>
      </tr>
    @endforeach
  </table>
</div>
<div style="margin-top:16px;">{{ $utilisateurs->links() }}</div>
@endsection
