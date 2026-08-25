@extends('layouts.societaire')
@section('title', 'Mon Compte')
@section('content')

<style>
.mc-hero{display:grid;grid-template-columns:1.25fr .75fr;gap:24px;align-items:stretch;}
.mc-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);}
.mc-title{font-family:'Sora';font-size:24px;margin:0 0 4px;letter-spacing:-.02em;}
.mc-sub{margin:0 0 24px;color:var(--muted);font-size:14px;line-height:1.7;}
.solde-hero{background:linear-gradient(135deg,var(--navy),var(--navy-2));border-radius:var(--radius);color:#fff;padding:26px;position:relative;overflow:hidden;}
.solde-hero::after{content:'';position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(232,163,61,.35),transparent 70%);}
.solde-hero .lbl{font-size:12px;text-transform:uppercase;letter-spacing:.1em;color:#b9c6ea;font-weight:700;}
.solde-hero .val{font-family:'Sora';font-size:34px;font-weight:800;margin:10px 0 6px;position:relative;}
.solde-hero .note{font-size:12px;color:#b9c6ea;}
.acct-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:18px;}
.acct-box{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:14px 16px;}
.acct-box .lbl{font-size:11px;color:#b9c6ea;font-weight:700;text-transform:uppercase;letter-spacing:.06em;}
.acct-box .val{font-family:'Sora';font-weight:800;font-size:17px;margin-top:5px;}
.acct-box .meta{font-size:11px;color:#8fa0cf;margin-top:2px;}
.stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.stat-box{background:var(--bg);border-radius:12px;padding:16px;border:1px solid transparent;transition:all .2s;}
.stat-box:hover{border-color:var(--line);transform:translateY(-2px);}
.stat-box .lbl{font-size:11px;font-weight:700;text-transform:uppercase;color:var(--muted);letter-spacing:.08em;}
.stat-box .val{font-family:'Sora';font-size:20px;font-weight:800;margin-top:8px;color:var(--navy);}
.panel{margin-top:24px;}
.panel-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px;flex-wrap:wrap;}
.panel-head h2{font-size:17px;}
.op-table th,.op-table td{white-space:nowrap;}
.badge{display:inline-flex;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.02em;}
.b-green{background:var(--green-bg);color:var(--green);} .b-amber{background:var(--amber-bg);color:var(--gold-2);}
.b-red{background:var(--red-bg);color:var(--red);} .b-navy{background:#e8edfb;color:var(--navy);}
.sig-ok{display:inline-flex;align-items:center;gap:5px;color:var(--green);font-size:12px;font-weight:600;}
.sig-ok svg{flex:none;}
.stepper{display:flex;align-items:flex-start;margin:18px 0 6px;padding:0 4px;}
.step{flex:1;position:relative;text-align:center;}
.step:not(:last-child)::after{content:'';position:absolute;top:15px;left:50%;width:100%;height:2.5px;background:var(--line);z-index:0;}
.step.done:not(:last-child)::after{background:var(--gold);}
.step .dot{width:31px;height:31px;margin:0 auto;border-radius:50%;background:var(--bg);border:2.5px solid var(--line);display:flex;align-items:center;justify-content:center;color:var(--muted);font-weight:800;font-size:12px;position:relative;z-index:1;transition:all .25s;}
.step.done .dot{background:var(--gold);border-color:var(--gold);color:var(--navy);}
.step.current .dot{border-color:var(--gold);background:var(--surface);color:var(--gold-2);box-shadow:0 0 0 4px rgba(232,163,61,.18);}
.step.rejected .dot{background:var(--red);border-color:var(--red);color:#fff;}
.step .lbl{font-size:11px;font-weight:700;margin-top:8px;color:var(--muted);}
.step.done .lbl{color:var(--gold-2);}
.step.current .lbl{color:var(--navy);}
.step.rejected .lbl{color:var(--red);}
.notif-item{display:flex;gap:14px;padding:14px 20px;border-bottom:1px solid var(--line);transition:background .2s;align-items:flex-start;}
.notif-item:last-child{border-bottom:none;}
.notif-item:hover{background:var(--bg);}
.notif-item.unread{background:#fdf8ec;}
.notif-ic{width:38px;height:38px;flex:none;border-radius:10px;background:#e8edfb;color:var(--navy);display:flex;align-items:center;justify-content:center;}
.notif-body{flex:1;min-width:0;}
.notif-body .txt{font-size:13px;line-height:1.55;color:var(--ink);}
.notif-body .when{font-size:11.5px;color:var(--muted);margin-top:3px;}
.notif-dot{width:9px;height:9px;border-radius:50%;background:var(--gold);flex:none;margin-top:6px;}
.empty{padding:32px 16px;text-align:center;color:var(--muted);font-size:13px;}
.card-body{padding:20px;}
.btn{border:none;border-radius:9px;padding:9px 15px;font-weight:700;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;text-decoration:none;font-family:'Manrope',sans-serif;}
.btn-gold{background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);}
.btn-ghost{background:var(--surface);border:1px solid var(--line);color:var(--ink);}
.sig-thumb{max-height:42px;max-width:110px;border:1px solid var(--line);border-radius:6px;background:#fff;padding:2px;}
@media(max-width:1024px){.mc-hero{grid-template-columns:1fr;}}
@media(max-width:640px){.acct-grid,.stat-grid{grid-template-columns:1fr;}}
</style>

<h1 class="mc-title">Mon Compte</h1>
<p class="mc-sub">Consultez votre solde, suivez l'avancement de vos opérations et vos notifications en temps réel.</p>

<section class="mc-hero">
    <div class="solde-hero">
        <div class="lbl">Solde global de l'épargne</div>
        <div class="val">{{ number_format($societaire->soldeTotalEpargne(), 0, ',', ' ') }} F</div>
        <div class="note">Épargne totale (DAV + DAT + tontine LOGOKU)</div>
        <div class="acct-grid">
            @forelse($societaire->comptesEpargne as $compte)
                <div class="acct-box">
                    <div class="lbl">Compte {{ $compte->type }}</div>
                    <div class="val">{{ number_format($compte->solde, 0, ',', ' ') }} F</div>
                    <div class="meta">Ouvert le {{ $compte->date_ouverture->format('d/m/Y') }}
                        @if($compte->plafond_retrait_journalier) — Plafond : {{ number_format($compte->plafond_retrait_journalier, 0, ',', ' ') }} F/j @endif
                    </div>
                </div>
            @empty
                <div class="acct-box"><div class="lbl">Aucun compte</div><div class="val" style="font-size:13px;">Aucun compte d'épargne ouvert.</div></div>
            @endforelse
            @if($societaire->compteTontine)
                <div class="acct-box">
                    <div class="lbl">Tontine LOGOKU</div>
                    <div class="val">{{ number_format($societaire->compteTontine->solde_accumule, 0, ',', ' ') }} F</div>
                    <div class="meta">Plafond crédit adossé : {{ number_format($societaire->compteTontine->plafondCreditAdosse(), 0, ',', ' ') }} F</div>
                </div>
            @endif
        </div>
    </div>

    <div class="mc-card">
        <div class="card-head"><h2>Vue d'ensemble</h2></div>
        <div class="card-body">
            <div class="stat-grid">
                <div class="stat-box"><div class="lbl">Sociétaire n°</div><div class="val" style="font-size:15px;">{{ $societaire->numero_societaire }}</div></div>
                <div class="stat-box"><div class="lbl">Agence</div><div class="val" style="font-size:15px;">{{ $societaire->agence->nom ?? '—' }}</div></div>
                <div class="stat-box"><div class="lbl">Crédits en cours</div><div class="val">{{ $societaire->credits->where('statut', 'validee')->count() }}</div></div>
                <div class="stat-box"><div class="lbl">Crédits sollicités</div><div class="val">{{ $societaire->credits->count() }}</div></div>
            </div>
        </div>
    </div>
</section>

<section class="panel">
    <div class="panel-head">
        <h2>Suivi de mes opérations</h2>
    </div>
    <div class="mc-card">
        <div class="card-body" style="padding:0;">
            <table class="op-table">
                <tr>
                    <th>Opération</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Signature</th>
                </tr>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>
                            <strong>{{ $transaction->libelleType() }}</strong>
                            <div style="font-size:11.5px;color:var(--muted);margin-top:2px;">
                                @if($transaction->compteEpargne) Compte {{ $transaction->compteEpargne->type }}
                                @elseif($transaction->credit) Crédit #{{ $transaction->credit_id }}
                                @else — @endif
                            </div>
                        </td>
                        <td><strong>{{ number_format($transaction->montant, 0, ',', ' ') }} F</strong></td>
                        <td>{{ $transaction->date_operation->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($transaction->statut === 'validee')
                                <span class="badge b-green">Validée</span>
                            @elseif($transaction->statut === 'annulee')
                                <span class="badge b-red">Annulée</span>
                            @else
                                <span class="badge b-amber">{{ ucfirst($transaction->statut) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($transaction->signature)
                                <span class="sig-ok"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>Signé le {{ $transaction->signe_le?->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="badge b-amber">Non signée</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Aucune opération pour le moment. Effectuez un dépôt, un retrait ou un remboursement pour démarrer.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
</section>

<section class="panel">
    <div class="panel-head">
        <h2>Avancement de mes demandes de crédit</h2>
    </div>
    <div class="mc-card">
        <div class="card-body">
            @forelse($credits as $credit)
                <div style="border:1px solid var(--line);border-radius:14px;padding:20px;margin-bottom:16px;background:var(--bg);">
                    <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:center;">
                        <div>
                            <strong style="font-size:14.5px;">{{ $credit->libelleType() }}</strong>
                            <div style="font-size:12.5px;color:var(--muted);margin-top:3px;">
                                {{ number_format($credit->montant, 0, ',', ' ') }} F — Demandé le {{ $credit->date_demande->format('d/m/Y') }}
                                @if($credit->signature_societaire) — <span class="sig-ok">Signé</span> @endif
                            </div>
                        </div>
                        @if($credit->progression())
                            <span class="badge {{ $credit->statut === 'validee' ? 'b-green' : 'b-navy' }}">{{ $credit->libelleStatut() }}</span>
                        @else
                            <span class="badge {{ $credit->statut === 'rejetee' ? 'b-red' : 'b-green' }}">{{ $credit->libelleStatut() }}</span>
                        @endif
                    </div>

                    @if($credit->progression())
                        @php $prog = $credit->progression(); @endphp
                        <div class="stepper">
                            @foreach(['recue', 'en_instruction', 'transmise_gerant', 'validee'] as $i => $step)
                                @php
                                    $cls = $step === 'validee' ? ($credit->statut === 'validee' ? 'done' : '') : ($prog > $i + 1 ? 'done' : ($prog === $i + 1 ? 'current' : ''));
                                    $label = match($step) { 'recue' => 'Reçue', 'en_instruction' => 'En instruction', 'transmise_gerant' => 'Transmise au gérant', 'validee' => 'Validée' };
                                    $icon = $cls === 'done' ? '✓' : ($i + 1);
                                @endphp
                                <div class="step {{ $cls }}">
                                    <div class="dot">{{ $icon }}</div>
                                    <div class="lbl">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($credit->statut === 'rejetee')
                        <div style="margin-top:14px;padding:12px 16px;background:var(--red-bg);border-radius:10px;color:var(--red);font-size:13px;font-weight:600;">
                            Votre demande a été rejetée. Merci de contacter l'agence pour plus d'informations.
                        </div>
                    @elseif($credit->statut === 'soldee')
                        <div style="margin-top:14px;padding:12px 16px;background:var(--green-bg);border-radius:10px;color:var(--green);font-size:13px;font-weight:600;">
                            Crédit entièrement remboursé et soldé. Félicitations !
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty">Aucune demande de crédit pour le moment.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="panel">
    <div class="panel-head">
        <h2>Mes notifications</h2>
        <form method="POST" action="{{ route('societaire.notifications.lues') }}">
            @csrf
            <button class="btn btn-ghost" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Tout marquer comme lu
            </button>
        </form>
    </div>
    <div class="mc-card">
        @forelse($notifications as $notification)
            <div class="notif-item {{ $notification->lu ? '' : 'unread' }}">
                <div class="notif-ic">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </div>
                <div class="notif-body">
                    <div class="txt">{{ $notification->contenu }}</div>
                    <div class="when">{{ $notification->date_envoi?->format('d/m/Y à H:i') }}</div>
                </div>
                @if(!$notification->lu)<div class="notif-dot"></div>@endif
            </div>
        @empty
            <div class="empty">Aucune notification. Vous serez notifié à chaque opération effectuée.</div>
        @endforelse
    </div>
</section>

@endsection
