@extends('layouts.app')
@section('title', 'Modifier un utilisateur')
@section('content')
<div class="card card-pad" style="max-width:640px;">
  <form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    <div class="grid g2">
      <div class="field"><label>Nom</label><input name="nom" value="{{ old('nom', $user->nom) }}" required></div>
      <div class="field"><label>Prénom</label><input name="prenom" value="{{ old('prenom', $user->prenom) }}" required></div>
    </div>
    <div class="grid g2">
      <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
      <div class="field"><label>Téléphone</label><input name="telephone" value="{{ old('telephone', $user->telephone) }}"></div>
    </div>
    <div class="grid g2">
      <div class="field">
        <label>Rôle principal</label>
        <select name="role" id="roleSelect" required onchange="
          document.getElementById('seuilBlock').style.display = this.value === 'gerant' ? 'block' : 'none';
          document.getElementById('zoneBlock').style.display = this.value === 'agent_promotion' ? 'block' : 'none';
          document.querySelectorAll('.role-additionnel').forEach(cb => cb.disabled = cb.value === this.value);
        ">
          @foreach($roles as $r)
            <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label>Agence</label>
        <select name="agence_id" required>
          @foreach($agences as $a)
            <option value="{{ $a->id }}" {{ $user->agence_id === $a->id ? 'selected' : '' }}>{{ $a->nom }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="field">
      <label>Rôles additionnels</label>
      @php $additionnels = array_values(array_diff($user->rolesAttribues(), [$user->role])); @endphp
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;">
        @foreach($roles as $r)
          <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
            <input type="checkbox" name="roles_additionnels[]" value="{{ $r }}" class="role-additionnel"
                   {{ in_array($r, $additionnels) ? 'checked' : '' }}
                   {{ $r === $user->role ? 'disabled' : '' }}>
            {{ ucfirst(str_replace('_',' ',$r)) }}
          </label>
        @endforeach
      </div>
      <small style="color:var(--muted);font-size:11px;">Un utilisateur peut cumuler plusieurs rôles. Le rôle principal détermine son tableau de bord.</small>
    </div>
    <div class="field" id="seuilBlock" style="display:{{ $user->role === 'gerant' ? 'block' : 'none' }};">
      <label>Seuil de validation crédit (F CFA)</label>
      <input type="number" step="0.01" name="seuil_validation" value="{{ old('seuil_validation', $user->seuil_validation) }}">
    </div>
    <div class="field" id="zoneBlock" style="display:{{ $user->role === 'agent_promotion' ? 'block' : 'none' }};">
      <label>Zone de tournée</label>
      <input name="zone_tournee" value="{{ old('zone_tournee', $user->zone_tournee) }}">
    </div>
    <div class="field">
      <label>Couleur d'identification</label>
      <div style="display:flex;align-items:center;gap:12px;">
        <input type="color" name="couleur" value="{{ old('couleur', $user->couleur ?? '#011f62') }}" style="width:50px;height:40px;padding:2px;border-radius:8px;border:1px solid var(--line);cursor:pointer;">
        <span style="font-size:12px;color:var(--muted);">Choisissez une couleur pour identifier cet utilisateur</span>
      </div>
    </div>
    <button class="btn btn-navy" type="submit"><x-icon name="save" size="16"/> Enregistrer les modifications</button>
  </form>
</div>
@endsection
