@extends('layouts.app')
@section('title', 'Nouvel utilisateur')
@section('content')
<div class="card card-pad" style="max-width:640px;">
  <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="grid g2">
      <div class="field"><label>Nom</label><input name="nom" required></div>
      <div class="field"><label>Prénom</label><input name="prenom" required></div>
    </div>
    <div class="grid g2">
      <div class="field"><label>Email</label><input type="email" name="email" required></div>
      <div class="field"><label>Téléphone</label><input name="telephone"></div>
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
            <option value="{{ $r }}">{{ ucfirst(str_replace('_',' ',$r)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label>Agence</label>
        <select name="agence_id" required>
          @foreach($agences as $a)
            <option value="{{ $a->id }}">{{ $a->nom }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="field">
      <label>Rôles additionnels</label>
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;">
        @foreach($roles as $r)
          <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;">
            <input type="checkbox" name="roles_additionnels[]" value="{{ $r }}" class="role-additionnel">
            {{ ucfirst(str_replace('_',' ',$r)) }}
          </label>
        @endforeach
      </div>
      <small style="color:var(--muted);font-size:11px;">Un utilisateur peut cumuler plusieurs rôles. Le rôle principal détermine son tableau de bord.</small>
    </div>
    <div class="field" id="seuilBlock" style="display:none;"><label>Seuil de validation crédit (F CFA)</label><input type="number" step="0.01" name="seuil_validation"></div>
    <div class="field" id="zoneBlock" style="display:none;"><label>Zone de tournée</label><input name="zone_tournee"></div>
    <div class="grid g2">
      <div class="field">
        <label>Mot de passe</label>
        <input type="password" name="password" id="password" required minlength="8">
        <small style="color:var(--muted);font-size:11px;">Confidentiel : à communiquer à l'utilisateur après la création.</small>
      </div>
      <div class="field">
        <label>Confirmation du mot de passe</label>
        <input type="password" name="password_confirmation" required minlength="8">
      </div>
    </div>
    <button class="btn btn-navy" type="submit"><x-icon name="plus" size="16"/> Créer le compte</button>
  </form>
</div>
@endsection
