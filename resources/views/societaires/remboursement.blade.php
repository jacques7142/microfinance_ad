@extends('layouts.societaire')
@section('title', 'Remboursement de crédit')
@section('content')

<style>
.page-title{font-family:'Sora';font-size:24px;margin:0 0 4px;letter-spacing:-.02em;}
.page-sub{color:var(--muted);margin:0 0 28px;font-size:14px;line-height:1.6;}
.credit-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:24px;margin-bottom:20px;transition:all .25s cubic-bezier(.4,0,.2,1);}
.credit-card:hover{box-shadow:0 12px 40px rgba(1,31,98,.12);}
.credit-card h2{margin:0 0 4px;font-family:'Sora';font-size:18px;letter-spacing:-.01em;}
.credit-card .sub{margin:0 0 18px;color:var(--muted);font-size:13px;line-height:1.5;}
.badge{display:inline-flex;padding:5px 12px;border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.02em;}
.b-green{background:var(--green-bg);color:var(--green);} .b-amber{background:var(--amber-bg);color:var(--gold-2);} .b-red{background:var(--red-bg);color:var(--red);}
table{width:100%;border-collapse:collapse;font-size:13px;}
th{text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:var(--muted);padding:12px 14px 10px 14px;border-bottom:1px solid var(--line);letter-spacing:.06em;}
td{padding:12px 14px;border-bottom:1px solid var(--line);transition:all .25s cubic-bezier(.4,0,.2,1);}
tr:last-child td{border-bottom:none;}
tr:hover td{background:var(--bg);}
.btn{border:none;border-radius:10px;padding:9px 16px;font-weight:700;font-size:12px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;text-decoration:none;font-family:'Manrope',sans-serif;transition:all .25s cubic-bezier(.4,0,.2,1);}
.btn-gold{background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(232,163,61,.35);}
.btn-gold:active{transform:translateY(0);}
.btn-ghost{background:var(--surface);border:1px solid var(--line);color:var(--ink);}
.btn-ghost:hover{background:var(--bg);border-color:#cdd3e1;transform:translateY(-1px);}
.empty{text-align:center;padding:48px 24px;color:var(--muted);}
.empty p{font-size:14px;margin:0 0 16px;}
.alert{padding:14px 18px;border-radius:12px;font-size:13px;margin-bottom:20px;font-weight:500;display:flex;align-items:center;gap:10px;animation:slideDown .3s ease;}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
.alert-success{background:var(--green-bg);color:var(--green);border:1px solid #c5e4d3;}
.alert-error{background:var(--red-bg);color:var(--red);border:1px solid #f5cdd2;}
.modal{display:none;position:fixed;inset:0;background:rgba(1,31,98,.5);-webkit-backdrop-filter:blur(4px);backdrop-filter:blur(4px);align-items:center;justify-content:center;z-index:999;animation:modalFade .25s ease;}
.modal.active{display:flex;}
@keyframes modalFade{from{opacity:0}to{opacity:1}}
.modal-box{background:#fff;border-radius:var(--radius);padding:28px;max-width:420px;width:90%;box-shadow:0 24px 64px rgba(1,31,98,.2);animation:modalSlide .3s ease;}
@keyframes modalSlide{from{opacity:0;transform:translateY(20px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
.modal-box h3{margin:0 0 6px;font-family:'Sora';font-size:18px;letter-spacing:-.01em;}
.modal-box p{margin:0 0 20px;color:var(--muted);font-size:13px;line-height:1.5;}
.modal-box p strong{color:var(--ink);}
.modal-box .field{margin-bottom:16px;}
.modal-box .field label{display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em;}
.modal-box .field input:not([type="radio"]){width:100%;padding:12px 14px;border-radius:10px;border:1.5px solid var(--line);font-size:14px;font-family:'Manrope',sans-serif;transition:all .25s cubic-bezier(.4,0,.2,1);}
.modal-box .field select{width:100%;padding:12px 14px;border-radius:10px;border:1.5px solid var(--line);font-size:14px;font-family:'Manrope',sans-serif;transition:all .25s cubic-bezier(.4,0,.2,1);background:#fff;}
.modal-box .field input:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 4px rgba(1,31,98,.08);}
.modal-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:4px;}
.modal-actions .btn{width:auto;}
@media(max-width:768px){.content{padding:24px 20px;}}
@media(max-width:640px){.content{padding:20px 16px;}.credit-card{padding:18px;}table{font-size:12px;}th,td{padding:10px 10px;}.page-title{font-size:20px;}.modal-box{padding:24px;}}
</style>

<h1 class="page-title">Remboursement de crédit</h1>
<p class="page-sub">Sélectionnez une échéance et effectuez votre remboursement.</p>
@if(app(\App\Services\LigdiCashService::class)->estEnModeDemo())
<div class="alert" style="background:var(--amber-bg);color:var(--gold-2);border:1px solid rgba(201,127,30,.28);font-size:12.5px;margin-bottom:20px;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>Mode démonstration : le paiement est simulé et confirmé automatiquement (Mixx by Yas / Flooz).</div>
@endif

@if(session('success'))<div class="alert alert-success"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-error"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $errors->first() }}</div>@endif

@php $hasCredits = false; @endphp

@foreach($societaire->credits as $credit)
    @if($credit->echeances->count() > 0 && $credit->statut !== 'soldee')
        @php $hasCredits = true; @endphp
        <div class="credit-card">
            <h2>{{ $credit->libelleType() }}</h2>
            <p class="sub">Montant : <strong>{{ number_format($credit->montant, 0, ',', ' ') }} F</strong> — Durée : {{ $credit->duree_mois }} mois — Taux : {{ $credit->taux_interet }} %</p>
            <table>
                <tr>
                    <th>Échéance</th>
                    <th>Montant dû</th>
                    <th>Payé</th>
                    <th>Reste</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
                @foreach($credit->echeances as $echeance)
                    <tr>
                        <td>{{ $echeance->date_echeance->format('d/m/Y') }}</td>
                        <td>{{ number_format($echeance->montant_du, 0, ',', ' ') }} F</td>
                        <td>{{ number_format($echeance->montant_paye, 0, ',', ' ') }} F</td>
                        <td><strong>{{ number_format(max(0, $echeance->montant_du - $echeance->montant_paye), 0, ',', ' ') }} F</strong></td>
                        <td>
                            @if($echeance->statut === 'payee')
                                <span class="badge b-green">Payée</span>
                            @elseif($echeance->estEnRetard())
                                <span class="badge b-red">En retard</span>
                            @else
                                <span class="badge b-amber">À venir</span>
                            @endif
                        </td>
                        <td>
                            @if($echeance->statut !== 'payee')
                                <button class="btn btn-gold" onclick="openModal({{ $echeance->id }}, '{{ $echeance->date_echeance->format('d/m/Y') }}', {{ number_format(max(0, $echeance->montant_du - $echeance->montant_paye), 2, '.', '') }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                                    Rembourser
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
@endforeach

@if(!$hasCredits)
    <div class="empty">
        <p>Aucun crédit avec des échéances à rembourser pour le moment.</p>
        <a href="{{ route('societaire.credit.create') }}" class="btn btn-gold" style="padding:11px 20px;font-size:13px;">Demander un crédit</a>
    </div>
@endif

<div class="modal" id="remboursementModal">
    <div class="modal-box">
        <h3>Rembourser une échéance</h3>
        <p>Échéance du <strong id="modalDate"></strong></p>
        <form method="POST" action="{{ route('societaire.remboursement.store') }}">
            @csrf
            <input type="hidden" name="echeance_id" id="modalEcheanceId">
            <div class="field">
                <label>Montant du remboursement (F CFA)</label>
                <input type="number" step="100" min="1" name="montant" id="modalMontant" required>
            </div>
            <div class="field">
                <label>Moyen de paiement</label>
                <x-moyen-paiement id="op" :value="old('operateur', 'yas')" required />
            </div>
            <div class="field">
                <label>Numéro de paiement (mobile money)</label>
                <input type="tel" name="telephone" value="{{ old('telephone', $societaire->telephone) }}" required placeholder="Ex: 90 00 00 00">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="closeModal()">Annuler</button>
                <button class="btn btn-gold" type="submit">Confirmer le remboursement</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id, date, maxMontant) {
    document.getElementById('modalEcheanceId').value = id;
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalMontant').max = maxMontant;
    document.getElementById('modalMontant').placeholder = (window.coopecI18n ? window.coopecI18n.t('Max : ') : 'Max : ') + maxMontant.replace('.', ',') + ' F';
    document.getElementById('remboursementModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    if (window.reinitSignaturePads) window.reinitSignaturePads();
}
function closeModal() {
    document.getElementById('remboursementModal').classList.remove('active');
    document.body.style.overflow = '';
}
document.getElementById('remboursementModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection