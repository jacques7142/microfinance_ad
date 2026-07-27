@extends('layouts.app')
@section('title', 'Ma tournée — Tontine LOGOKU')
@section('content')
<div class="grid" style="grid-template-columns:1.2fr 1fr;gap:16px;">
  <div>
    <div class="card">
      <table>
        <tr><th>Membre</th><th>Solde accumulé</th><th>Mise habituelle</th><th>Statut</th><th></th></tr>
        @forelse($comptes as $compte)
          <tr>
            <td>{{ $compte->societaire->nomComplet() }}</td>
            <td>{{ number_format($compte->solde_accumule,0,',',' ') }} F</td>
            <td>{{ number_format($compte->mise_habituelle ?? 0,0,',',' ') }} F</td>
            <td>
              @if($collectesDuJour->contains($compte->id))
                <span class="badge b-green">Collecté aujourd'hui</span>
              @else
                <span class="badge b-amber">À visiter</span>
              @endif
            </td>
            <td>
              <button class="btn btn-navy btn-sm" onclick="preremplir({{ $compte->id }}, '{{ $compte->societaire->nomComplet() }}', {{ $compte->mise_habituelle ?? 0 }})">Collecter</button>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" style="color:#5c6479;">Aucun membre dans votre zone de tournée.</td></tr>
        @endforelse
      </table>
    </div>
  </div>

  <div class="card card-pad">
    <h3 style="font-size:14px;margin-bottom:6px;">Enregistrer une collecte</h3>
    <p id="membreLabel" style="color:#5c6479;font-size:12.5px;margin-bottom:14px;">Sélectionnez un membre dans la liste.</p>
    <form method="POST" action="{{ route('tontine.collecter') }}">
      @csrf
      <input type="hidden" name="compte_tontine_id" id="compteTontineId" required>
      <div class="field"><label>Montant de la mise (F CFA)</label><input type="number" step="0.01" name="montant" id="montantInput" required></div>
      <div class="field"><label>Lieu</label><input name="lieu" placeholder="Domicile, marché, lieu d'activité..."></div>
      <div class="field"><label>Mode de confirmation</label>
        <select name="mode_confirmation">
          <option value="signature">Signature sur écran</option>
          <option value="otp_sms">Code OTP par SMS</option>
        </select>
      </div>
      <button class="btn btn-gold" type="submit">Enregistrer la collecte</button>
    </form>
  </div>
</div>

<script>
function preremplir(compteId, nom, mise) {
  document.getElementById('compteTontineId').value = compteId;
  document.getElementById('membreLabel').textContent = 'Membre sélectionné : ' + nom;
  document.getElementById('montantInput').value = mise || '';
}
</script>
@endsection
