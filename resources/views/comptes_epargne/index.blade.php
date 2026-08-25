@extends('layouts.app')
@section('title', 'Guichet — Dépôt / retrait')
@section('content')
<div class="grid" style="grid-template-columns:1fr 1.2fr;gap:16px;">
  <div class="card card-pad">
    <h3 style="font-size:14px;margin-bottom:14px;">1. Choisir le sociétaire</h3>
    <div class="field">
      <label>Sociétaire</label>
      <select id="societaireSelect" onchange="chargerComptes(this.value)">
        <option value="">— Sélectionner —</option>
        @foreach($societaires as $s)
          <option value="{{ $s->id }}">{{ $s->numero_societaire }} — {{ $s->nom }} {{ $s->prenom }}</option>
        @endforeach
      </select>
    </div>
    <div id="comptesZone" style="margin-top:16px;"></div>
  </div>

  <div class="card card-pad">
    <h3 style="font-size:14px;margin-bottom:14px;">2. Opération</h3>
    <form method="POST" action="{{ route('epargne.operation') }}">
      @csrf
      <div class="field">
        <label>Compte</label>
        <select name="compte_epargne_id" id="compteSelect" required>
          <option value="">— Choisir un sociétaire d'abord —</option>
        </select>
      </div>
      <div class="field">
        <label>Type d'opération</label>
        <select name="type" required>
          <option value="depot">Dépôt</option>
          <option value="retrait">Retrait</option>
        </select>
      </div>
      <div class="field"><label>Montant (F CFA)</label><input type="number" step="0.01" name="montant" required></div>
      <button class="btn btn-navy" type="submit"><x-icon name="save" size="16"/> Valider l'opération</button>
    </form>
  </div>
</div>

<script>
async function chargerComptes(societaireId) {
  const zone = document.getElementById('comptesZone');
  const select = document.getElementById('compteSelect');
  if (!societaireId) {
    zone.innerHTML = '';
    var opt = '— Choisir un sociétaire d\'abord —';
    if (window.coopecI18n) opt = window.coopecI18n.t(opt);
    select.innerHTML = '<option value="">' + opt + '</option>';
    return;
  }

  const res = await fetch(`/guichet/societaires/${societaireId}/comptes`);
  const html = await res.text();
  zone.innerHTML = html;

  // Remplit le select des comptes à partir des données injectées dans le fragment (data-attributes)
  const options = zone.querySelectorAll('[data-compte-id]');
  select.innerHTML = '';
  options.forEach(opt => {
    const o = document.createElement('option');
    o.value = opt.dataset.compteId;
    const t = window.coopecI18n ? window.coopecI18n.t : (s) => s;
    o.textContent = t(opt.dataset.label);
    select.appendChild(o);
  });
}
</script>
@endsection
