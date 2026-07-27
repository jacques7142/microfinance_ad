<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — COOPEC-AD</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --navy:#011f62; --navy-2:#0a3a8f; --gold:#e8a33d; --gold-2:#c97f1e; --muted:#a1b0d0;
  --bg:#f3f6fb; --surface:#ffffff; --ink:#101a2e; --line:#e6e9f2; --radius:18px;
}
*{box-sizing:border-box;} body{margin:0;font-family:'Manrope',sans-serif;background:var(--bg);color:var(--ink);}
h1,h2,h3{font-family:'Sora',sans-serif;margin:0;}
.wrap{min-height:100vh;display:flex;}
.left{flex:1;background:linear-gradient(160deg,#011f62,#04275f);color:#fff;padding:56px 48px;display:flex;flex-direction:column;justify-content:center;}
.right{flex:.9;background:var(--bg);display:flex;align-items:center;justify-content:center;padding:40px;}
.hero-brand{display:flex;align-items:center;gap:10px;}
.hero-brand .mark{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--gold),var(--gold-2));display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:800;color:var(--navy);font-size:14px;}
.hero-brand .company{font-family:'Sora';font-weight:800;font-size:15px;}
.hero-copy{color:#c3cee6;font-size:13.5px;margin-top:22px;max-width:420px;line-height:1.8;}
.kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-top:44px;}
.kpi{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.09);border-radius:14px;padding:18px;}
.kpi .value{font-family:'Sora';font-size:22px;font-weight:800;}
.kpi .label{font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);margin-top:8px;}
.hero-note{font-size:11px;color:rgba(255,255,255,.65);margin-top:18px;max-width:360px;}
.card{width:100%;max-width:420px;background:#fff;border-radius:20px;box-shadow:0 24px 48px rgba(1,31,98,.12);padding:32px;}
.tabs{display:flex;gap:8px;margin-bottom:24px;background:#eef2f8;padding:7px;border-radius:999px;}
.tab{display:inline-flex;flex:1;align-items:center;justify-content:center;border:none;border-radius:999px;background:transparent;color:#5c6479;font-size:13px;font-weight:700;padding:11px 16px;cursor:pointer;text-decoration:none;}
.tab.active{background:#fff;box-shadow:0 4px 12px rgba(1,31,98,.08);color:var(--navy);}
.card h1{font-size:24px;line-height:1.2;margin-bottom:8px;}
.card p{color:#5c6479;font-size:14px;line-height:1.75;margin-bottom:24px;}
.field{margin-bottom:16px;}
.field label{display:block;font-size:12px;font-weight:700;color:#5c6479;margin-bottom:8px;}
.field input{width:100%;padding:14px 16px;border-radius:14px;border:1px solid var(--line);font-size:14px;background:#f9fbff;}
.actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;}
.actions label{display:flex;align-items:center;gap:8px;font-size:13px;color:#5c6479;cursor:pointer;}
.actions input{width:16px;height:16px;border:1px solid var(--line);border-radius:4px;background:#fff;}
.actions .forgot{color:var(--navy);text-decoration:none;font-weight:700;}
.btn{width:100%;border:none;border-radius:14px;padding:13px 20px;font-weight:700;font-size:14px;cursor:pointer;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);}
.alert{background:#fbeae8;color:#c4453b;padding:14px 16px;border-radius:14px;font-size:13px;margin-bottom:22px;}
.info{background:#fff7e4;border:1px solid #f6e3b4;border-radius:14px;padding:14px 16px;font-size:13px;color:#8a5a12;display:flex;align-items:center;gap:10px;margin-top:18px;}
.info svg{width:18px;height:18px;flex:none;}
.footer-text{margin-top:20px;font-size:13px;text-align:center;color:#6b7280;}
.footer-text a{color:var(--navy);text-decoration:none;font-weight:700;}
@media(max-width:1060px){.wrap{flex-direction:column;} .left,.right{padding:32px;} .kpis{grid-template-columns:repeat(2,minmax(0,1fr));}}
@media(max-width:700px){.hero-copy,.hero-note{max-width:none;} .kpis{grid-template-columns:1fr;} .card{padding:24px;}}
</style>
</head>
<body>
@php
    $accountType = old('account_type', 'societaire');
    $identifierLabel = $accountType === 'societaire' ? 'Numéro sociétaire ou téléphone' : 'Email';
    $identifierPlaceholder = $accountType === 'societaire' ? 'ex. 90 12 34 56' : 'ex. utilisateur@coopec-ad.tg';
@endphp
<div class="wrap">
  <section class="left">
    <div class="hero-brand"><div class="mark">CA</div><div class="company">COOPEC-AD Togo</div></div>
    <h1 style="font-size:40px;line-height:1.05;max-width:520px;margin-top:30px;">« Le vrai bonheur », géré simplement, agence par agence.</h1>
    <p class="hero-copy">Sociétaires, épargne DAV/DAT, crédits, tontine LOGOKU et reporting multi-agences réunis dans un seul espace sécurisé.</p>
    <div class="kpis">
      <div class="kpi"><div class="value">{{ number_format($agenceCount, 0, ',', ' ') }}</div><div class="label">agences</div></div>
      <div class="kpi"><div class="value">{{ number_format($societaireCount, 0, ',', ' ') }}</div><div class="label">sociétaires</div></div>
      <div class="kpi"><div class="value">{{ number_format($utilisateurCount, 0, ',', ' ') }}</div><div class="label">collaborateurs</div></div>
      <div class="kpi"><div class="value">{{ number_format($profilCount, 0, ',', ' ') }}</div><div class="label">profils métiers</div></div>
    </div>
    <p class="hero-note">Institution de microfinance agréée SFD — Direction de la Microfinance, zone UEMOA.</p>
  </section>

  <section class="right">
    <div class="card">
      <div class="tabs">
        <button type="button" class="tab {{ $accountType === 'societaire' ? 'active' : '' }}" data-account="societaire">Sociétaire</button>
        <button type="button" class="tab {{ $accountType === 'interne' ? 'active' : '' }}" data-account="interne">Personnel interne</button>
        <a href="{{ route('register') }}" class="tab" style="text-align:center;text-decoration:none;">S'inscrire</a>
      </div>

      <h1>Bon retour parmi nous</h1>
      <p>Connectez-vous pour accéder à votre espace.</p>

      @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <input type="hidden" name="account_type" id="account_type" value="{{ $accountType }}">
        <div class="field">
          <label id="identifierLabel">{{ $identifierLabel }}</label>
          <input id="identifier" type="text" name="identifier" value="{{ old('identifier') }}" placeholder="{{ $identifierPlaceholder }}" required autofocus autocomplete="username">
        </div>
        <div class="field">
          <label>Mot de passe</label>
          <input type="password" name="password" required autocomplete="current-password">
        </div>
        <div class="actions">
          <label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Se souvenir de moi</label>
          <a href="#" class="forgot">Mot de passe oublié ?</a>
        </div>
        <button class="btn" type="submit">Se connecter</button>
      </form>

      <div class="info">
        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1C4.686 1 2 3.686 2 7v2c0 1.795.995 3.354 2.492 4.27A1.75 1.75 0 0 0 6 15h4a1.75 1.75 0 0 0 1.508-.73C13.005 12.354 14 10.795 14 9V7c0-3.314-2.686-6-6-6Zm0 2c2.21 0 4 1.79 4 4v2c0 1.21-.654 2.289-1.664 2.884A.25.25 0 0 1 10.25 11H5.75a.25.25 0 0 1-.086-.016C3.654 10.289 3 9.21 3 8V7c0-2.21 1.79-4 4-4Zm-.75 4.5h1.5V8H7.25V5.5Zm0 3h1.5V12H7.25V8.5Z" fill="#8A5A12"/></svg>
        Connexion chiffrée · Toutes les actions sensibles sont journalisées à des fins de traçabilité.
      </div>
      <p class="footer-text">Pas encore sociétaire ? <a href="#">Adhérer à la COOPEC-AD</a></p>
    </div>
  </section>
</div>
<script>
    const tabs = document.querySelectorAll('.tab');
    const hiddenType = document.getElementById('account_type');
    const identifierLabel = document.getElementById('identifierLabel');
    const identifierField = document.getElementById('identifier');

    const placeholders = {
        societaire: 'ex. 90 12 34 56',
        interne: 'ex. utilisateur@coopec-ad.tg',
    };

    const labels = {
        societaire: 'Numéro sociétaire ou téléphone',
        interne: 'Email',
    };

    function setAccountType(type) {
        hiddenType.value = type;
        identifierLabel.textContent = labels[type];
        identifierField.placeholder = placeholders[type];

        tabs.forEach(tab => {
            tab.classList.toggle('active', tab.dataset.account === type);
        });
    }

    tabs.forEach(tab => tab.addEventListener('click', () => setAccountType(tab.dataset.account)));
</script>
</body>
</html>
