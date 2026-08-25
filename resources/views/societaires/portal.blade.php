@extends('layouts.societaire')
@section('title', 'Tableau de bord')
@section('content')

<style>
    .stats-charts{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:28px;}
    .chart-panel{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:20px;}
    .chart-panel h3{font-size:14px;margin:0 0 4px;}
    .chart-panel .hint{font-size:11.5px;color:var(--muted);margin:0 0 14px;}
    .chart-panel .chart-wrap{position:relative;height:220px;}
    @media(max-width:760px){.stats-charts{grid-template-columns:1fr;}}

    .hero{display:grid;grid-template-columns:1.25fr .75fr;gap:24px;align-items:start;}
    .hero-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:28px;transition:all .25s cubic-bezier(.4,0,.2,1);}
    .hero-card:hover{box-shadow:0 12px 40px rgba(1,31,98,.12);}
    .hero-title{font-family:'Sora';font-size:26px;margin:0 0 8px;letter-spacing:-.02em;}
    .hero-meta{margin:0 0 20px;color:var(--muted);line-height:1.7;font-size:14px;}
    .summary-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;}
    .stat-box{background:var(--bg);border-radius:12px;padding:16px;transition:all .25s cubic-bezier(.4,0,.2,1);border:1px solid transparent;}
    .stat-box:hover{border-color:var(--line);background:#eff2fa;transform:translateY(-2px);box-shadow:0 1px 3px rgba(1,31,98,.06);}
    .stat-box .lbl{font-size:11px;font-weight:700;text-transform:uppercase;color:var(--muted);letter-spacing:.08em;}
    .stat-box .val{font-family:'Sora';font-size:20px;font-weight:800;margin-top:8px;color:var(--navy);overflow-wrap:anywhere;line-height:1.3;}
    .action-buttons{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:22px;}
    .btn-action{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:20px 12px 16px;border-radius:12px;background:var(--bg);border:1px solid var(--line);text-decoration:none;color:var(--navy);transition:all .25s cubic-bezier(.4,0,.2,1);text-align:center;position:relative;overflow:hidden;}
    .btn-action::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--navy),var(--navy-2));opacity:0;transition:all .25s cubic-bezier(.4,0,.2,1);border-radius:inherit;}
    .btn-action:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(1,31,98,.18);border-color:var(--navy);}
    .btn-action:hover::before{opacity:1;}
    .btn-action svg{flex:none;position:relative;z-index:1;transition:all .25s cubic-bezier(.4,0,.2,1);}
    .btn-action:hover svg{transform:scale(1.1);}
    .action-label{font-family:'Sora';font-size:14px;font-weight:700;position:relative;z-index:1;transition:all .25s cubic-bezier(.4,0,.2,1);}
    .action-desc{font-size:11px;color:var(--muted);line-height:1.3;position:relative;z-index:1;transition:all .25s cubic-bezier(.4,0,.2,1);}
    .btn-action:hover .action-label{color:#fff;}
    .btn-action:hover .action-desc{color:rgba(255,255,255,.7);}
    .btn-action:active{transform:translateY(-1px);}
    .panel{margin-top:28px;display:grid;gap:20px;}
    .info-row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:18px;}
    .info-block{background:var(--bg);border-radius:12px;padding:18px;transition:all .25s cubic-bezier(.4,0,.2,1);border:1px solid transparent;}
    .info-block:hover{border-color:var(--line);background:#eff2fa;transform:translateY(-2px);}
    .info-block p{margin:0 0 4px;font-size:12px;font-weight:600;text-transform:uppercase;color:var(--muted);letter-spacing:.06em;}
    .info-block strong{font-family:'Sora';font-size:20px;color:var(--navy);font-weight:800;overflow-wrap:anywhere;line-height:1.3;display:block;}
    .badge{display:inline-flex;padding:5px 12px;border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.02em;}
    .b-navy{background:#e8edfb;color:var(--navy);} .b-green{background:#e8f6ef;color:#1e8a5f;} .b-amber{background:var(--amber-bg);color:var(--gold-2);} .b-red{background:var(--red-bg);color:var(--red);}
    .empty-state{text-align:center;padding:32px 16px;color:var(--muted);font-size:13px;}

    .ops{margin-top:28px;}
    .ops-tabs{display:flex;gap:6px;background:var(--surface);border:1px solid var(--line);border-bottom:none;border-radius:var(--radius) var(--radius) 0 0;padding:10px 12px 0;overflow-x:auto;box-shadow:var(--shadow);}
    .ops-tab{border:none;background:none;cursor:pointer;padding:11px 16px;font-family:'Sora';font-weight:700;font-size:13px;color:var(--muted);border-bottom:3px solid transparent;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;transition:all .2s;border-radius:8px 8px 0 0;}
    .ops-tab:hover{color:var(--navy);background:var(--bg);}
    .ops-tab.active{color:var(--navy);border-bottom-color:var(--gold);background:var(--bg);}
    .ops-tab svg{opacity:.7;}
    .ops-panels{background:var(--surface);border:1px solid var(--line);border-radius:0 0 var(--radius) var(--radius);box-shadow:var(--shadow);padding:24px;}
    .ops-panel{display:none;animation:fadeIn .25s ease;}
    .ops-panel.active{display:block;}
    @keyframes fadeIn{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:translateY(0)}}
    .ops-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;}
    @media(max-width:760px){.ops-grid{grid-template-columns:1fr;}}
    .ops-form .field label{display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em;}
    .ops-form .field input:not([type="radio"]),.ops-form .field select{width:100%;padding:12px 14px;border-radius:10px;border:1.5px solid var(--line);font-size:14px;font-family:'Manrope',sans-serif;background:#fafcff;transition:all .2s;color:var(--ink);}
    .ops-form .field input:focus,.ops-form .field select:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 4px rgba(1,31,98,.08);}
    .ops-submit{border:none;border-radius:11px;padding:13px 18px;font-weight:700;font-size:14px;cursor:pointer;font-family:'Manrope',sans-serif;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);display:inline-flex;align-items:center;gap:8px;transition:all .2s;}
    .ops-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(232,163,61,.35);}
    .ops-note{font-size:12.5px;color:var(--muted);line-height:1.6;margin:0 0 18px;}
    .echeance-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:11px 14px;border:1px solid var(--line);border-radius:10px;margin-bottom:10px;background:var(--bg);}
    .echeance-row .info{font-size:13px;}
    .echeance-row .info strong{display:block;font-size:13.5px;}
    .echeance-row .meta{font-size:11.5px;color:var(--muted);margin-top:2px;}
    .ops-empty{text-align:center;padding:22px;color:var(--muted);font-size:13px;}
    .ops-link{display:inline-flex;align-items:center;gap:6px;color:var(--navy);font-weight:700;font-size:13px;text-decoration:none;margin-top:6px;}
    @media(max-width:1024px){.hero{grid-template-columns:1fr;} .summary-grid{grid-template-columns:repeat(2,minmax(0,1fr));} .action-buttons{grid-template-columns:repeat(2,minmax(0,1fr));}}
    @media(max-width:640px){.hero-card{padding:22px;} .summary-grid{grid-template-columns:1fr;} .action-buttons{grid-template-columns:1fr;} .info-row{grid-template-columns:1fr;} .hero-title{font-size:22px;} .ops-panels{padding:18px 14px;}}
</style>

<section class="hero">
    <div class="hero-card">
        <div class="hero-title">Bienvenue, {{ $societaire->prenom }}</div>
        <p class="hero-meta">Accédez à votre espace personnel pour suivre vos comptes, consulter votre épargne et vos crédits, et rester informé des services de la COOPEC-AD.</p>
        <div class="summary-grid">
            <div class="stat-box"><div class="lbl">Sociétaire</div><div class="val">{{ $societaire->numero_societaire }}</div></div>
            <div class="stat-box"><div class="lbl">Agence</div><div class="val">{{ $societaire->agence->nom ?? '—' }}</div></div>
            <div class="stat-box"><div class="lbl">Téléphone</div><div class="val">{{ $societaire->telephone }}</div></div>
            <div class="stat-box"><div class="lbl">Statut</div><div class="val">{{ ucfirst($societaire->statut) }}</div></div>
        </div>
        <div class="action-buttons">
            <a href="{{ route('societaire.credit.create') }}" class="btn-action">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                <span class="action-label">Faire un prêt</span>
                <span class="action-desc">Soumettre une demande de crédit</span>
            </a>
            <a href="{{ route('societaire.remboursement') }}" class="btn-action">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                <span class="action-label">Rembourser un prêt</span>
                <span class="action-desc">Payer vos échéances</span>
            </a>
            <a href="{{ route('societaire.depot') }}" class="btn-action">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span class="action-label">Dépôt d'argent</span>
                <span class="action-desc">Créditer votre compte épargne</span>
            </a>
            <a href="{{ route('societaire.retrait') }}" class="btn-action">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M19 17H9.5a3.5 3.5 0 0 0 0-7h5a3.5 3.5 0 0 1 0-7H6"/></svg>
                <span class="action-label">Retrait d'argent</span>
                <span class="action-desc">Débiter votre compte épargne</span>
            </a>
        </div>
    </div>

    <div class="hero-card">
        <div class="card-head" style="border-bottom:none;padding:0 0 6px;"><h2 style="font-size:16px;">Solde et crédit</h2></div>
        <div class="card-body" style="padding-top:8px;">
            <div class="info-row">
                <div class="info-block"><p>Épargne totale</p><strong>{{ number_format($societaire->soldeTotalEpargne(), 0, ',', ' ') }} F</strong></div>
                <div class="info-block"><p>Crédits en cours</p><strong>{{ $societaire->credits->where('statut', 'validee')->count() }}</strong></div>
            </div>
            @if($societaire->compteTontine)
            <div class="info-row" style="margin-top:12px;">
                <div class="info-block"><p>Solde tontine LOGOKU</p><strong>{{ number_format($societaire->compteTontine->solde_accumule, 0, ',', ' ') }} F</strong></div>
                <div class="info-block"><p>Plafond crédit adossé</p><strong>{{ number_format($societaire->compteTontine->plafondCreditAdosse(), 0, ',', ' ') }} F</strong></div>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="stats-charts">
    <div class="chart-panel">
        <h3>Répartition de mon épargne</h3>
        <p class="hint">Vos comptes d'épargne (DAV, DAT) et la tontine LOGOKU.</p>
        <div class="chart-wrap"><canvas id="chartEpargne"></canvas></div>
    </div>
    <div class="chart-panel">
        <h3>Mes demandes de crédit</h3>
        <p class="hint">Statut de vos demandes de crédit en cours.</p>
        <div class="chart-wrap"><canvas id="chartCredits"></canvas></div>
    </div>
</section>

<section class="ops">
    <div class="ops-tabs" role="tablist">
        <button class="ops-tab active" data-tab="depot" onclick="switchTab('depot')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Dépôt
        </button>
        <button class="ops-tab" data-tab="retrait" onclick="switchTab('retrait')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M19 17H9.5a3.5 3.5 0 0 0 0-7h5a3.5 3.5 0 0 1 0-7H6"/></svg>
            Retrait
        </button>
        <button class="ops-tab" data-tab="remboursement" onclick="switchTab('remboursement')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            Remboursement
        </button>
        <button class="ops-tab" data-tab="credit" onclick="switchTab('credit')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            Demande de prêt
        </button>
    </div>
    <div class="ops-panels">
        @if(app(\App\Services\LigdiCashService::class)->estEnModeDemo())
        <div style="display:flex;align-items:center;gap:8px;background:var(--amber-bg);color:var(--gold-2);border:1px solid rgba(201,127,30,.28);padding:11px 14px;border-radius:10px;font-size:12.5px;font-weight:600;margin-bottom:18px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Mode démonstration : les paiements (Mixx by Yas / Flooz) sont simulés et confirmés automatiquement.
        </div>
        @endif

        <div class="ops-panel ops-form active" id="panel-depot">
            <h3 style="font-size:16px;margin:0 0 6px;">Dépôt d'argent</h3>
            <p class="ops-note">Créditez l'un de vos comptes d'épargne. L'opération est immédiate.</p>
            <form method="POST" action="{{ route('societaire.depot.store') }}">
                @csrf
                <div class="ops-grid">
                    <div class="field">
                        <label>Compte à créditer</label>
                        <select name="compte_epargne_id" required>
                            <option value="">— Sélectionnez un compte —</option>
                            @foreach($societaire->comptesEpargne as $compte)
                                <option value="{{ $compte->id }}" {{ old('compte_epargne_id') == $compte->id ? 'selected' : '' }}>{{ $compte->type }} — Solde : {{ number_format($compte->solde, 0, ',', ' ') }} F</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Montant du dépôt (F CFA)</label>
                        <input type="number" step="100" min="100" name="montant" value="{{ old('montant') }}" required placeholder="Ex: 50 000">
                    </div>
                </div>
                <div class="field">
                    <label>Moyen de paiement</label>
                    <x-moyen-paiement id="pd" :value="old('operateur', 'yas')" required />
                </div>
                <div class="field">
                    <label>Numéro de paiement (mobile money)</label>
                    <input type="tel" name="telephone" value="{{ old('telephone', $societaire->telephone) }}" required placeholder="Ex: 90 00 00 00">
                </div>
                <button class="ops-submit" type="submit">Payer et effectuer le dépôt</button>
            </form>
        </div>

        <div class="ops-panel ops-form" id="panel-retrait">
            <h3 style="font-size:16px;margin:0 0 6px;">Retrait d'argent</h3>
            <p class="ops-note">Débitez l'un de vos comptes d'épargne. Vérifiez le plafond journalier.</p>
            <form method="POST" action="{{ route('societaire.retrait.store') }}">
                @csrf
                <div class="ops-grid">
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
                </div>
                <div class="field">
                    <label>Moyen de réception</label>
                    <x-moyen-paiement id="pr" :value="old('operateur', 'yas')" required />
                </div>
                <div class="field">
                    <label>Numéro de réception (mobile money)</label>
                    <input type="tel" name="telephone" value="{{ old('telephone', $societaire->telephone) }}" required placeholder="Ex: 90 00 00 00">
                </div>
                <button class="ops-submit" type="submit">Effectuer le retrait</button>
            </form>
        </div>

        <div class="ops-panel ops-form" id="panel-remboursement">
            <h3 style="font-size:16px;margin:0 0 6px;">Remboursement de crédit</h3>
            <p class="ops-note">Sélectionnez une échéance et effectuez votre remboursement.</p>
            @php $hasRemboursable = false; @endphp
            @foreach($societaire->credits as $credit)
                @if($credit->echeances->count() > 0 && $credit->statut !== 'soldee')
                    @php $hasRemboursable = true; @endphp
                    <div style="margin-bottom:16px;">
                        <div style="font-family:'Sora';font-weight:700;font-size:13.5px;margin-bottom:10px;">{{ $credit->libelleType() }}</div>
                        @foreach($credit->echeances as $echeance)
                            @if($echeance->statut !== 'payee')
                                @php $reste = max(0, (float)$echeance->montant_du - (float)$echeance->montant_paye); @endphp
                                <div class="echeance-row" style="flex-direction:column;align-items:stretch;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                                        <div class="info">
                                            <strong>Échéance du {{ $echeance->date_echeance->format('d/m/Y') }}</strong>
                                            <div class="meta">Reste dû : {{ number_format($reste, 0, ',', ' ') }} F
                                                @if($echeance->estEnRetard())<span class="badge b-red">En retard</span>@endif
                                            </div>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('societaire.remboursement.store') }}">
                                        @csrf
                                        <input type="hidden" name="echeance_id" value="{{ $echeance->id }}">
                                        <div style="display:flex;gap:8px;align-items:center;margin-bottom:10px;">
                                            <input type="number" name="montant" step="100" min="1" max="{{ $reste }}" placeholder="Montant" required style="flex:1;min-width:0;padding:9px 11px;border-radius:9px;border:1.5px solid var(--line);font-family:inherit;font-size:13px;">
                                            <button class="ops-submit" type="submit" style="padding:9px 14px;font-size:12.5px;">Payer</button>
                                        </div>
                                        <div class="field">
                                            <label>Moyen de paiement</label>
                                            <x-moyen-paiement id="rm-{{ $echeance->id }}" :value="old('operateur', 'yas')" required />
                                        </div>
                                        <div class="field">
                                            <label>Numéro de paiement (mobile money)</label>
                                            <input type="tel" name="telephone" value="{{ $societaire->telephone }}" required placeholder="Ex: 90 00 00 00">
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endforeach
            @if(!$hasRemboursable)
                <div class="ops-empty">Aucune échéance à rembourser pour le moment.</div>
            @endif
        </div>

        <div class="ops-panel ops-form" id="panel-credit">
            <h3 style="font-size:16px;margin:0 0 6px;">Demande de prêt</h3>
            <p class="ops-note">Votre dossier est enregistré avec le statut « Reçue » et sera traité par nos équipes.</p>
            <form method="POST" action="{{ route('societaire.credit.store') }}">
                @csrf
                <div class="ops-grid">
                    <div class="field">
                        <label>Type de crédit</label>
                        <select name="type" id="creditType" onchange="toggleCreditType()" required>
                            <option value="{{ App\Models\Credit::TYPE_ORDINAIRE }}" {{ old('type') === App\Models\Credit::TYPE_ORDINAIRE ? 'selected' : '' }}>Crédit ordinaire</option>
                            <option value="{{ App\Models\Credit::TYPE_PARTENARIAT }}" {{ old('type') === App\Models\Credit::TYPE_PARTENARIAT ? 'selected' : '' }}>Crédit de partenariat</option>
                            <option value="{{ App\Models\Credit::TYPE_TONTINE }}" {{ old('type') === App\Models\Credit::TYPE_TONTINE ? 'selected' : '' }}>Crédit tontine adossé</option>
                        </select>
                    </div>
                    <div class="field" id="creditSousTypeBlock" style="display:none;">
                        <label>Sous-type (crédit ordinaire)</label>
                        <select name="sous_type">
                            <option value="">— Sélectionnez —</option>
                            @foreach($sousTypesOrdinaire as $st)
                                <option value="{{ $st }}" {{ old('sous_type') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$st)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field" id="creditPartenaireBlock" style="display:none;">
                        <label>Partenaire</label>
                        <input type="text" name="partenaire" value="{{ old('partenaire') }}">
                    </div>
                    <div class="field">
                        <label>Montant sollicité (F CFA)</label>
                        <input type="number" step="0.01" name="montant" value="{{ old('montant') }}" required>
                    </div>
                    <div class="field">
                        <label>Durée (mois)</label>
                        <input type="number" name="duree_mois" value="{{ old('duree_mois', 12) }}" required>
                    </div>
                    <div class="field">
                        <label>Taux d'intérêt annuel (%)</label>
                        <input type="number" step="0.01" name="taux_interet" value="{{ old('taux_interet', 12) }}" required>
                    </div>
                </div>
                <x-signature-pad label="Signature du sociétaire" name="signature" />
                <button class="ops-submit" type="submit">Soumettre ma demande</button>
            </form>
        </div>

    </div>
</section>

<section class="panel">
    <div class="card">
        <div class="card-head"><h2>Comptes d'épargne</h2></div>
        <div class="card-body" style="padding-top:20px;">
            <table>
                <tr><th>Type</th><th>Solde</th><th>Ouvert le</th></tr>
                @forelse($societaire->comptesEpargne as $compte)
                    <tr>
                        <td>{{ $compte->type }}</td>
                        <td><strong>{{ number_format($compte->solde, 0, ',', ' ') }} F</strong></td>
                        <td>{{ $compte->date_ouverture->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty-state">Aucun compte d'épargne.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><h2>Crédits récents</h2></div>
        <div class="card-body" style="padding-top:20px;">
            <table>
                <tr><th>Type</th><th>Montant</th><th>Statut</th></tr>
                @forelse($credits as $credit)
                    <tr>
                        <td>{{ $credit->libelleType() }}</td>
                        <td>{{ number_format($credit->montant, 0, ',', ' ') }} F</td>
                        <td><span class="badge b-navy">{{ ucfirst(str_replace('_', ' ', $credit->statut)) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty-state">Aucune demande de crédit récente.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
</section>

<script>
function switchTab(tab) {
    document.querySelectorAll('.ops-tab').forEach(function(btn) {
        btn.classList.toggle('active', btn.dataset.tab === tab);
    });
    document.querySelectorAll('.ops-panel').forEach(function(panel) {
        panel.classList.toggle('active', panel.id === 'panel-' + tab);
    });
}
function toggleCreditType() {
    var type = document.getElementById('creditType').value;
    document.getElementById('creditSousTypeBlock').style.display = type === '{{ App\Models\Credit::TYPE_ORDINAIRE }}' ? 'block' : 'none';
    document.getElementById('creditPartenaireBlock').style.display = type === '{{ App\Models\Credit::TYPE_PARTENARIAT }}' ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleCreditType);

document.addEventListener('DOMContentLoaded', function () {
    function donut(id, data) {
        var el = document.getElementById(id);
        var values = data.map(function (x) { return x.total; });
        if (!el || !values.some(function (v) { return v > 0; })) {
            if (el) { el.parentElement.innerHTML = '<div style="height:100%;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12.5px;">Aucune donnée.</div>'; }
            return;
        }
        new Chart(el, {
            type: 'doughnut',
            data: {
                labels: data.map(function (x) { return x.label; }),
                datasets: [{ data: values, backgroundColor: ['#0a3a8f', '#e8a33d', '#1e8a5f', '#7c3aed', '#c4453b', '#0e7490'], borderWidth: 2, borderColor: 'var(--surface)' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 14, boxWidth: 8, font: { family: "'Manrope', sans-serif", size: 11 } } },
                    tooltip: { callbacks: { label: function (ctx) { var total = ctx.dataset.data.reduce(function (a, b) { return a + b; }, 0); var pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0; return ' ' + ctx.label + ' : ' + ctx.formattedValue + ' (' + pct + '%)'; } } }
                }
            }
        });
    }
    donut('chartEpargne', @json($epargneParType));
    donut('chartCredits', @json($creditsParStatut));
});
</script>
@endsection