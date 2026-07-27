<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace sociétaire — COOPEC-AD</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:#011f62; --navy-2:#0a3a8f; --gold:#e8a33d; --gold-2:#c97f1e;
            --bg:#f4f6fb; --surface:#fff; --ink:#101a2e; --muted:#5c6479; --line:#e6e9f2; --radius:16px;
            --shadow:0 8px 24px rgba(1,31,98,.12);
        }
        *{box-sizing:border-box;} body{margin:0;font-family:'Manrope',sans-serif;background:var(--bg);color:var(--ink);}
        .page{min-height:100vh;display:flex;flex-direction:column;}
        .header{display:flex;align-items:center;justify-content:space-between;padding:22px 28px;background:#fff;border-bottom:1px solid var(--line);}
        .brand{display:flex;align-items:center;gap:12px;}
        .mark{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--gold),var(--gold-2));display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:800;color:var(--navy);font-size:16px;}
        .brand-text{font-family:'Sora';font-size:16px;font-weight:800;line-height:1.2;}
        .brand-text span{display:block;font-size:12px;font-weight:500;color:var(--muted);margin-top:2px;}
        .btn{border:none;border-radius:10px;padding:10px 18px;font-weight:700;font-size:13px;cursor:pointer;}
        .btn-ghost{background:#fff;border:1px solid var(--line);color:var(--ink);}
        .content{max-width:1180px;width:100%;margin:0 auto;padding:28px;}
        .hero{display:grid;grid-template-columns:1.2fr .8fr;gap:24px;align-items:start;}
        .hero-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:28px;}
        .hero-title{font-family:'Sora';font-size:28px;margin:0 0 14px;}
        .hero-meta{margin:0;color:var(--muted);line-height:1.65;}
        .summary-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-top:22px;}
        .stat{background:var(--bg);border-radius:14px;padding:18px;}
        .stat .lbl{font-size:11px;font-weight:700;text-transform:uppercase;color:var(--muted);letter-spacing:.08em;}
        .stat .val{font-family:'Sora';font-size:24px;font-weight:800;margin-top:10px;color:var(--navy);}
        .panel{margin-top:24px;display:grid;gap:18px;}
        .card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);}
        .card-head{padding:18px 22px;border-bottom:1px solid var(--line);}
        .card-head h2{margin:0;font-family:'Sora';font-size:16px;}
        .card-body{padding:18px 22px;}
        table{width:100%;border-collapse:collapse;font-size:14px;}
        th{text-align:left;font-size:11px;text-transform:uppercase;color:var(--muted);padding:14px 16px;border-bottom:1px solid var(--line);}
        td{padding:14px 16px;border-bottom:1px solid var(--line);}
        .badge{display:inline-flex;padding:5px 11px;border-radius:999px;font-size:11px;font-weight:700;}
        .b-navy{background:#e8edfb;color:var(--navy);} .b-green{background:#e8f6ef;color:#1e8a5f;}
        .info-row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-top:18px;}
        .info-block{background:var(--bg);border-radius:14px;padding:18px;}
        .info-block p{margin:0;line-height:1.7;color:var(--muted);} .info-block strong{display:block;margin-top:8px;font-family:'Sora';font-size:18px;color:var(--navy);}
        @media(max-width:900px){.hero{grid-template-columns:1fr;} .summary-grid{grid-template-columns:repeat(2,minmax(0,1fr));} .info-row{grid-template-columns:1fr;}}
        @media(max-width:640px){.header,.content{padding:18px;} .summary-grid{grid-template-columns:1fr;} }
    </style>
</head>
<body>
<div class="page">
    <header class="header">
        <div class="brand">
            <div class="mark">CA</div>
            <div class="brand-text">COOPEC-AD Togo<span>Plateforme coopérative</span></div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost">Se déconnecter</button>
        </form>
    </header>

    <main class="content">
        <section class="hero">
            <div class="hero-card">
                <div class="hero-title">Bienvenue, {{ $societaire->prenom }}</div>
                <p class="hero-meta">Accédez à votre espace personnel pour suivre vos comptes, consulter votre épargne et vos crédits, et rester informé des services de la COOPEC-AD.</p>
                <div class="summary-grid">
                    <div class="stat"><div class="lbl">Sociétaire</div><div class="val">{{ $societaire->numero_societaire }}</div></div>
                    <div class="stat"><div class="lbl">Agence</div><div class="val">{{ $societaire->agence->nom ?? '—' }}</div></div>
                    <div class="stat"><div class="lbl">Téléphone</div><div class="val">{{ $societaire->telephone }}</div></div>
                    <div class="stat"><div class="lbl">Statut</div><div class="val">{{ ucfirst($societaire->statut) }}</div></div>
                </div>
                <div style="margin-top:22px;">
                    <a href="{{ route('societaire.credit.create') }}" class="btn btn-gold" style="display:inline-flex;align-items:center;justify-content:center;">Demander un crédit</a>
                </div>
            </div>

            <div class="hero-card">
                <div class="card-head"><h2>Solde et crédit</h2></div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-block"><p>Épargne totale</p><strong>{{ number_format($societaire->soldeTotalEpargne(), 0, ',', ' ') }} F</strong></div>
                        <div class="info-block"><p>Crédits en cours</p><strong>{{ $societaire->credits->where('statut', 'validee')->count() }}</strong></div>
                    </div>
                    @if($societaire->compteTontine)
                    <div class="info-row" style="margin-top:18px;">
                        <div class="info-block"><p>Solde tontine LOGOKU</p><strong>{{ number_format($societaire->compteTontine->solde_accumule, 0, ',', ' ') }} F</strong></div>
                        <div class="info-block"><p>Plafond crédit adossé</p><strong>{{ number_format($societaire->compteTontine->plafondCreditAdosse(), 0, ',', ' ') }} F</strong></div>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="card">
                <div class="card-head"><h2>Comptes d'épargne</h2></div>
                <div class="card-body">
                    <table>
                        <tr><th>Type</th><th>Solde</th><th>Ouvert le</th></tr>
                        @forelse($societaire->comptesEpargne as $compte)
                            <tr>
                                <td>{{ $compte->type }}</td>
                                <td>{{ number_format($compte->solde, 0, ',', ' ') }} F</td>
                                <td>{{ $compte->date_ouverture->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="color:var(--muted);">Aucun compte d'épargne.</td></tr>
                        @endforelse
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-head"><h2>Crédits récents</h2></div>
                <div class="card-body">
                    <table>
                        <tr><th>Type</th><th>Montant</th><th>Statut</th></tr>
                        @forelse($credits as $credit)
                            <tr>
                                <td>{{ $credit->libelleType() }}</td>
                                <td>{{ number_format($credit->montant, 0, ',', ' ') }} F</td>
                                <td><span class="badge b-navy">{{ ucfirst(str_replace('_', ' ', $credit->statut)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="color:var(--muted);">Aucune demande de crédit récente.</td></tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
