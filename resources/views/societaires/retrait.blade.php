@extends('layouts.societaire')
@section('title', 'Retrait d\'argent')
@section('content')

<style>
.content-wrapper{max-width:540px;width:100%;margin:0 auto;}
.page-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:32px;transition:all .25s cubic-bezier(.4,0,.2,1);}
.page-card:hover{box-shadow:0 12px 40px rgba(1,31,98,.12);}
.card-icon{width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#e8edfb,#d8e2f5);display:flex;align-items:center;justify-content:center;margin-bottom:18px;}
.card-icon svg{color:var(--navy);}
.page-card h1{margin:0 0 6px;font-family:'Sora';font-size:24px;letter-spacing:-.02em;}
.page-card .sub{margin:0 0 24px;color:var(--muted);line-height:1.7;font-size:14px;}
.field{margin-bottom:20px;}
.field label{display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:7px;text-transform:uppercase;letter-spacing:.06em;}
.field input:not([type="radio"]),.field select{width:100%;padding:13px 16px;border-radius:12px;border:1.5px solid var(--line);font-size:14px;font-family:'Manrope',sans-serif;background:#fafcff;transition:all .25s cubic-bezier(.4,0,.2,1);color:var(--ink);-webkit-appearance:none;appearance:none;}
.field input:focus,.field select:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 4px rgba(1,31,98,.08);background:#fff;}
.field input::placeholder{color:#b0b7cc;}
.field select{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%235c6479' d='M6 8L0 0h12z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:38px;}
.btn-submit{border:none;border-radius:12px;padding:14px 18px;font-weight:700;font-size:14px;cursor:pointer;width:100%;font-family:'Manrope',sans-serif;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);transition:all .25s cubic-bezier(.4,0,.2,1);position:relative;overflow:hidden;}
.btn-submit::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--gold-2),var(--gold));opacity:0;transition:all .25s cubic-bezier(.4,0,.2,1);}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(232,163,61,.35);}
.btn-submit:hover::before{opacity:1;}
.btn-submit:active{transform:translateY(0);}
.btn-submit span{position:relative;z-index:1;display:flex;align-items:center;justify-content:center;gap:8px;}
.alert{padding:14px 18px;border-radius:12px;font-size:13px;margin-bottom:20px;font-weight:500;display:flex;align-items:center;gap:10px;animation:slideDown .3s ease;}
@keyframes slideDown{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
.alert-success{background:#e8f6ef;color:#1e8a5f;border:1px solid #c5e4d3;}
.alert-error{background:#fdecef;color:#c9453b;border:1px solid #f5cdd2;}
@media(max-width:640px){.content{padding:20px 16px;}.page-card{padding:24px;}}
</style>

<div class="content-wrapper">
    @if(session('success'))<div class="alert alert-success"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-error"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ $errors->first() }}</div>@endif
    <div class="page-card">
        <div class="card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M19 17H9.5a3.5 3.5 0 0 0 0-7h5a3.5 3.5 0 0 1 0-7H6"/></svg>
        </div>
        <h1>Retrait d'argent</h1>
        <p class="sub">Effectuez un retrait depuis l'un de vos comptes d'épargne. Vérifiez le plafond journalier.</p>
        @if(app(\App\Services\LigdiCashService::class)->estEnModeDemo())
        <div class="alert" style="background:var(--amber-bg);color:var(--gold-2);border:1px solid rgba(201,127,30,.28);font-size:12.5px;margin-bottom:20px;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>Mode démonstration : le paiement est simulé et confirmé automatiquement (Mixx by Yas / Flooz).</div>
        @endif
        <form method="POST" action="{{ route('societaire.retrait.store') }}">
            @csrf
            <div class="field">
                <label>Compte à débiter</label>
                <select name="compte_epargne_id" required>
                    <option value="">— Sélectionnez un compte —</option>
                    @foreach($societaire->comptesEpargne as $compte)
                        <option value="{{ $compte->id }}" {{ old('compte_epargne_id') == $compte->id ? 'selected' : '' }}>{{ $compte->type }} — Solde : {{ number_format($compte->solde, 0, ',', ' ') }} F{{ $compte->plafond_retrait_journalier ? ' — Plafond : '.number_format($compte->plafond_retrait_journalier, 0, ',', ' ').' F/j' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Montant du retrait (F CFA)</label>
                <input type="number" step="100" min="100" name="montant" value="{{ old('montant') }}" required placeholder="Ex: 25 000">
            </div>
            <div class="field">
                <label>Moyen de réception</label>
                <x-moyen-paiement id="op" :value="old('operateur', 'yas')" required />
            </div>
            <div class="field">
                <label>Numéro de réception (mobile money)</label>
                <input type="tel" name="telephone" value="{{ old('telephone', $societaire->telephone) }}" required placeholder="Ex: 90 00 00 00">
            </div>
            <p class="sub" style="margin-bottom:18px;">Le montant sera envoyé sur votre compte mobile money après traitement. L'opération est confirmée automatiquement.</p>
            <button class="btn-submit" type="submit"><span><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M19 17H9.5a3.5 3.5 0 0 0 0-7h5a3.5 3.5 0 0 1 0-7H6"/></svg>Effectuer le retrait</span></button>
        </form>
    </div>
</div>

@endsection