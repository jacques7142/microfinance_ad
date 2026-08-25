@extends('layouts.societaire')
@section('title', 'Validation du paiement')
@section('content')

<style>
.status-wrap{max-width:520px;width:100%;margin:24px auto;}
.status-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:36px;text-align:center;}
.spinner{width:54px;height:54px;margin:0 auto 20px;border-radius:50%;border:4px solid var(--line);border-top-color:var(--gold);animation:spin 1s linear infinite;}
@keyframes spin{to{transform:rotate(360deg)}}
.status-icon{width:58px;height:58px;margin:0 auto 18px;border-radius:50%;display:flex;align-items:center;justify-content:center;animation:pop .35s ease;}
@keyframes pop{from{transform:scale(.6);opacity:0}to{transform:scale(1);opacity:1}}
.ok{background:var(--green-bg);color:var(--green);}
.err{background:var(--red-bg);color:var(--red);}
.status-card h1{margin:0 0 8px;font-family:'Sora';font-size:22px;letter-spacing:-.02em;}
.status-card .sub{margin:0 0 26px;color:var(--muted);font-size:14px;line-height:1.6;}
.recap{background:var(--bg);border-radius:12px;padding:20px;margin:0 0 24px;}
.recap-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;font-size:13.5px;border-bottom:1px solid var(--line);}
.recap-row:last-child{border-bottom:none;}
.recap-row .lbl{color:var(--muted);}
.recap-row .val{font-weight:700;color:var(--ink);}
.badge{display:inline-flex;padding:5px 12px;border-radius:999px;font-size:11px;font-weight:700;}
.b-yas{background:linear-gradient(135deg,#FFD200,#F0B400);color:#003070;} .b-flooz{background:linear-gradient(135deg,#F06020,#E04E10);color:#fff;}
.btn{border:none;border-radius:11px;padding:12px 20px;font-weight:700;font-size:13.5px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;font-family:'Manrope',sans-serif;transition:all .2s;}
.btn-gold{background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);}
.btn-ghost{background:var(--surface);border:1px solid var(--line);color:var(--ink);}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(232,163,61,.35);}
.btn-ghost:hover{background:var(--bg);}
.hidden{display:none!important;}
</style>

<div class="status-wrap">
    <div class="status-card" id="statusCard" data-statut="{{ $paiement->statut }}">
        <div id="pendingView" @if($paiement->estFinalise())class="hidden"@endif>
            @if(app(\App\Services\LigdiCashService::class)->estEnModeDemo())
                <div style="display:flex;align-items:center;justify-content:center;gap:8px;background:var(--amber-bg);color:var(--gold-2);border:1px solid rgba(201,127,30,.28);padding:10px 14px;border-radius:10px;font-size:12px;font-weight:600;margin:0 0 18px;">Mode démonstration : le paiement est confirmé automatiquement.</div>
            @else
                <div class="spinner"></div>
                <h1>Validez sur votre téléphone</h1>
                <p class="sub">Vous allez recevoir une invite <strong>{{ $paiement->operateur === 'yas' ? 'USSD Mixx by Yas' : 'Flooz' }}</strong>. Composez votre code PIN pour confirmer le paiement. Le traitement peut prendre quelques minutes.</p>
            @endif
        </div>

        <div id="successView" class="hidden">
            <div class="status-icon ok">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h1>Paiement confirmé</h1>
            <p class="sub">Votre opération a été enregistrée avec succès.</p>
            <a href="{{ route('societaire.dashboard') }}" class="btn btn-gold">Retour au tableau de bord</a>
        </div>

        <div id="errorView" class="hidden">
            <div class="status-icon err">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <h1>Paiement non abouti</h1>
            <p class="sub">Le paiement n'a pas pu être confirmé. Aucune somme n'a été débitée.<br>Veuillez réessayer.</p>
            <a href="{{ $paiement->type === 'depot' ? route('societaire.depot') : ($paiement->type === 'retrait' ? route('societaire.retrait') : route('societaire.remboursement')) }}" class="btn btn-gold">Réessayer</a>
        </div>

        <div class="recap">
            <div class="recap-row"><span class="lbl">Référence</span><span class="val">{{ $paiement->reference_interne }}</span></div>
            <div class="recap-row"><span class="lbl">Opération</span><span class="val">{{ $paiement->typeLibelle() }}</span></div>
            <div class="recap-row"><span class="lbl">Opérateur</span><span class="val"><span class="badge {{ $paiement->operateur === 'yas' ? 'b-yas' : 'b-flooz' }}">{{ $paiement->operateurLibelle() }}</span></span></div>
            <div class="recap-row"><span class="lbl">Numéro</span><span class="val">{{ $paiement->telephone }}</span></div>
            <div class="recap-row"><span class="lbl">Montant</span><span class="val">{{ number_format($paiement->montant, 0, ',', ' ') }} F</span></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var card = document.getElementById('statusCard');
    var statut = card.dataset.statut;
    var pending = document.getElementById('pendingView');
    var success = document.getElementById('successView');
    var error = document.getElementById('errorView');

    function afficher(s) {
        pending.classList.toggle('hidden', s !== 'pending');
        success.classList.toggle('hidden', s !== 'completed');
        error.classList.toggle('hidden', !(s === 'notcompleted' || s === 'failed'));
    }
    afficher(statut);

    // Déjà finalisé côté serveur : pas besoin de poller.
    if (statut === 'completed' || statut === 'notcompleted' || statut === 'failed') {
        return;
    }

    var url = '{{ route('societaire.paiement.statut.api', $paiement) }}';
    var timer = setInterval(function() {
        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                afficher(d.statut);
                if (d.finalise) { clearInterval(timer); }
            })
            .catch(function() {});
    }, 4000);
})();
</script>
@endpush
@endsection