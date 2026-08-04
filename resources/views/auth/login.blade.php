<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — COOPEC-AD</title>
<script>!function(){var t=localStorage.getItem('theme');if(t==='dark'||(!t&&window.matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.setAttribute('data-theme','dark')}();</script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800;900&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --navy:#011f62; --navy-2:#0a3a8f; --gold:#e8a33d; --gold-2:#c97f1e; --muted:#a1b0d0;
  --bg:#f3f6fb; --surface:#ffffff; --ink:#101a2e; --line:#e6e9f2; --radius:18px;
  --muted-text:#5c6479; --shadow:0 24px 48px rgba(1,31,98,.12);
}
[data-theme="dark"]{
  --navy:#1e3a5f; --navy-2:#2d5aa0; --gold:#e8a33d; --gold-2:#c97f1e; --muted:#7e93b0;
  --bg:#0c1222; --surface:#161e30; --ink:#e2e8f0; --line:#1e2a45; --radius:18px;
  --muted-text:#8a97b3; --shadow:0 24px 48px rgba(0,0,0,.4);
}
*{box-sizing:border-box;} body{margin:0;font-family:'Manrope',sans-serif;background:var(--bg);color:var(--ink);-webkit-font-smoothing:antialiased;}
h1,h2,h3{font-family:'Sora',sans-serif;margin:0;}

/* ===== Keyframes ===== */
@keyframes fadeInUp{from{opacity:0;transform:translateY(28px);}to{opacity:1;transform:translateY(0);}}
@keyframes fadeIn{from{opacity:0;}to{opacity:1;}}
@keyframes floatUp{0%{transform:translateY(0) scale(1);opacity:.9;}50%{transform:translateY(-24px) scale(1.06);opacity:1;}100%{transform:translateY(0) scale(1);opacity:.9;}}
@keyframes drift{0%{transform:translate(0,0);}50%{transform:translate(30px,-40px);}100%{transform:translate(0,0);}}
@keyframes shine{0%{background-position:-200% 0;}100%{background-position:200% 0;}}
@keyframes pulseGlow{0%,100%{box-shadow:0 4px 12px rgba(232,163,61,.18);}50%{box-shadow:0 8px 28px rgba(232,163,61,.45);}}

.wrap{min-height:100vh;display:flex;}
.left{flex:1;background:linear-gradient(160deg,#011f62,#04275f);color:#fff;padding:56px 48px;display:flex;flex-direction:column;justify-content:center;position:relative;overflow:hidden;}
.left::before,.left::after{content:'';position:absolute;border-radius:50%;pointer-events:none;}
.left::before{width:420px;height:420px;top:-120px;right:-140px;background:radial-gradient(circle,rgba(232,163,61,.16) 0%,transparent 70%);animation:drift 12s ease-in-out infinite;}
.left::after{width:360px;height:360px;bottom:-140px;left:-120px;background:radial-gradient(circle,rgba(10,58,143,.35) 0%,transparent 70%);animation:drift 16s ease-in-out infinite reverse;}
.left>*{position:relative;z-index:1;}
.orb{position:absolute;border-radius:50%;pointer-events:none;opacity:.5;animation:floatUp 9s ease-in-out infinite;z-index:0;}
.orb.a{width:10px;height:10px;background:rgba(232,163,61,.7);top:24%;left:12%;}
.orb.b{width:6px;height:6px;background:rgba(255,255,255,.6);top:62%;right:18%;animation-delay:1.5s;}
.orb.c{width:8px;height:8px;background:rgba(232,163,61,.5);top:12%;right:34%;animation-delay:3s;}
.orb.d{width:5px;height:5px;background:rgba(255,255,255,.5);bottom:20%;left:30%;animation-delay:4.5s;}

.right{flex:.9;background:var(--bg);display:flex;align-items:center;justify-content:center;padding:40px;position:relative;overflow:hidden;}
.right::before{content:'';position:absolute;top:-160px;right:-160px;width:380px;height:380px;border-radius:50%;background:radial-gradient(circle,rgba(10,58,143,.07) 0%,transparent 70%);animation:drift 14s ease-in-out infinite;pointer-events:none;}

.hero-brand{display:flex;align-items:center;gap:10px;animation:fadeInUp .7s ease both;}
.hero-brand .mark{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--gold),var(--gold-2));display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:800;color:var(--navy);font-size:14px;animation:pulseGlow 3.5s ease-in-out infinite;}
.hero-brand .company{font-family:'Sora';font-weight:800;font-size:15px;}
.hero-copy{color:#c3cee6;font-size:13.5px;margin-top:22px;max-width:420px;line-height:1.8;animation:fadeInUp .7s .15s ease both;}
.left h1{font-size:40px;line-height:1.05;max-width:520px;margin-top:30px;background:linear-gradient(90deg,#ffffff 0%,#ffffff 40%,#ffd27d 50%,#ffffff 60%,#ffffff 100%);background-size:220% auto;-webkit-background-clip:text;background-clip:text;color:transparent;animation:fadeInUp .7s .08s ease both,shine 6s ease-in-out infinite;}
.kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-top:44px;}
.kpi{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.09);border-radius:14px;padding:18px;transition:transform .3s ease,border-color .3s ease,background .3s ease,box-shadow .3s ease;animation:fadeInUp .7s ease both;}
.kpi:nth-child(1){animation-delay:.25s;} .kpi:nth-child(2){animation-delay:.35s;}
.kpi:nth-child(3){animation-delay:.45s;} .kpi:nth-child(4){animation-delay:.55s;}
.kpi:hover{transform:translateY(-6px);background:rgba(255,255,255,.12);border-color:rgba(232,163,61,.45);box-shadow:0 14px 30px rgba(0,0,0,.25);}
.kpi .value{font-family:'Sora';font-size:22px;font-weight:800;}
.kpi .label{font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);margin-top:8px;}
.hero-note{font-size:11px;color:rgba(255,255,255,.65);margin-top:18px;max-width:360px;animation:fadeInUp .7s .65s ease both;}

.card{width:100%;max-width:420px;background:var(--surface);border-radius:20px;box-shadow:var(--shadow);padding:32px;animation:fadeInUp .8s .1s cubic-bezier(.22,.68,0,1) both;}
.card h1{font-size:24px;line-height:1.2;margin-bottom:8px;animation:fadeInUp .6s .25s ease both;}
.card p{color:var(--muted-text);font-size:14px;line-height:1.75;margin-bottom:24px;animation:fadeInUp .6s .32s ease both;}
.field{margin-bottom:16px;animation:fadeInUp .6s .4s ease both;}

.field label{display:block;font-size:12px;font-weight:700;color:var(--muted-text);margin-bottom:8px;}
.field input{width:100%;padding:14px 16px;border-radius:14px;border:1px solid var(--line);font-size:14px;background:var(--bg);color:var(--ink);transition:border-color .3s ease,box-shadow .3s ease,background .3s ease,transform .3s ease;}
.field input:focus{outline:none;border-color:var(--gold);background:var(--surface);box-shadow:0 0 0 4px rgba(232,163,61,.14),0 8px 20px rgba(1,31,98,.06);transform:translateY(-1px);}
.field input::placeholder{color:var(--muted);font-size:13px;}
.actions{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;animation:fadeInUp .6s .48s ease both;}
.actions label{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--muted-text);cursor:pointer;}
.actions input{width:16px;height:16px;border:1px solid var(--line);border-radius:4px;background:#fff;accent-color:var(--gold);cursor:pointer;}
.actions .forgot{color:var(--navy);text-decoration:none;font-weight:700;transition:color .3s ease;}
.actions .forgot:hover{color:var(--gold-2);text-decoration:underline;}
.btn{width:100%;border:none;border-radius:14px;padding:13px 20px;font-weight:700;font-size:14px;cursor:pointer;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);position:relative;overflow:hidden;transition:transform .3s ease,box-shadow .3s ease,filter .3s ease;animation:fadeInUp .6s .56s ease both;}
.btn::after{content:'';position:absolute;inset:0;background:linear-gradient(120deg,transparent 20%,rgba(255,255,255,.55) 50%,transparent 80%);background-size:200% auto;opacity:0;transition:opacity .3s ease;}
.btn:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(201,127,30,.4);filter:brightness(1.04);}
.btn:hover::after{opacity:1;animation:shine 1.4s linear infinite;}
.btn:active{transform:translateY(0) scale(.98);}
.alert{background:#fbeae8;color:#c4453b;padding:14px 16px;border-radius:14px;font-size:13px;margin-bottom:22px;animation:fadeIn .4s ease both;}
.info{background:#fff7e4;border:1px solid #f6e3b4;border-radius:14px;padding:14px 16px;font-size:13px;color:#8a5a12;display:flex;align-items:center;gap:10px;margin-top:18px;animation:fadeInUp .6s .64s ease both;transition:border-color .3s ease,transform .3s ease;}
.info:hover{border-color:#e9c766;transform:translateY(-2px);}
.info svg{width:18px;height:18px;flex:none;animation:floatUp 4s ease-in-out infinite;}
[data-theme="dark"] .info{background:rgba(232,163,61,.10);border-color:rgba(232,163,61,.20);color:var(--gold-2);}
[data-theme="dark"] .info svg path{fill:var(--gold-2);}
[data-theme="dark"] .alert{background:rgba(220,38,38,.10);border:1px solid rgba(220,38,38,.15);color:#fca5a5;}

.theme-btn{position:absolute;top:20px;right:28px;background:var(--surface);border:1px solid var(--line);border-radius:12px;width:42px;height:42px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);z-index:10;transition:all .25s;box-shadow:0 2px 8px rgba(0,0,0,.04);}
.theme-btn:hover{border-color:var(--gold);color:var(--gold);}
[data-theme="dark"] .theme-btn .moon-icon{display:block !important;}
[data-theme="dark"] .theme-btn .sun-icon{display:none !important;}
:root:not([data-theme="dark"]) .theme-btn .sun-icon{display:block !important;}

.divider{display:flex;align-items:center;gap:12px;margin:20px 0 16px;color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.08em;animation:fadeInUp .6s .6s ease both;}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--line);}
.btn-secondary{width:100%;display:block;text-align:center;text-decoration:none;border:2px solid var(--navy-2);border-radius:14px;padding:12px 20px;font-weight:700;font-size:14px;cursor:pointer;background:transparent;color:var(--navy-2);transition:all .25s;animation:fadeInUp .6s .62s ease both;}
.btn-secondary:hover{background:rgba(10,58,143,.06);transform:translateY(-1px);box-shadow:0 8px 20px rgba(1,31,98,.08);}
[data-theme="dark"] .btn-secondary{border-color:var(--gold);color:var(--gold);}
[data-theme="dark"] .btn-secondary:hover{background:rgba(232,163,61,.10);}
.footer-text{margin-top:18px;font-size:13px;text-align:center;color:var(--muted-text);animation:fadeInUp .6s .66s ease both;}
.footer-text a{color:var(--navy);text-decoration:none;font-weight:700;transition:color .3s ease;}
.footer-text a:hover{color:var(--gold-2);}
[data-theme="dark"] .footer-text a{color:var(--gold);}
@media(max-width:1060px){.wrap{flex-direction:column;} .left,.right{padding:32px;} .kpis{grid-template-columns:repeat(2,minmax(0,1fr));}}
@media(max-width:700px){.hero-copy,.hero-note{max-width:none;} .kpis{grid-template-columns:1fr;} .card{padding:24px;}}
</style>
</head>
<body>
<div class="wrap">
  <section class="left">
    <span class="orb a"></span>
    <span class="orb b"></span>
    <span class="orb c"></span>
    <span class="orb d"></span>
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
    <button id="themeToggle" class="theme-btn" aria-label="Changer le thème">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sun-icon" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="moon-icon" style="display:none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
    </button>

    <div class="card">
      <h1>Bon retour parmi nous</h1>
      <p>Connectez-vous avec votre téléphone ou votre email pour accéder à votre espace.</p>

      @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="field">
          <label>Téléphone ou email</label>
          <input type="text" name="identifier" value="{{ old('identifier') }}" placeholder="ex. 90 12 34 56 ou email" required autofocus autocomplete="username">
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

      <div class="divider">ou</div>

      <a href="{{ route('register') }}" class="btn-secondary">Créer un compte sociétaire</a>
      <p class="footer-text">Pas encore sociétaire ? <a href="{{ route('register') }}">Adhérer à la COOPEC-AD</a></p>

      <div class="info">
        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1C4.686 1 2 3.686 2 7v2c0 1.795.995 3.354 2.492 4.27A1.75 1.75 0 0 0 6 15h4a1.75 1.75 0 0 0 1.508-.73C13.005 12.354 14 10.795 14 9V7c0-3.314-2.686-6-6-6Zm0 2c2.21 0 4 1.79 4 4v2c0 1.21-.654 2.289-1.664 2.884A.25.25 0 0 1 10.25 11H5.75a.25.25 0 0 1-.086-.016C3.654 10.289 3 9.21 3 8V7c0-2.21 1.79-4 4-4Zm-.75 4.5h1.5V8H7.25V5.5Zm0 3h1.5V12H7.25V8.5Z" fill="#8A5A12"/></svg>
        Connexion chiffrée · Toutes les actions sensibles sont journalisées à des fins de traçabilité.
      </div>
    </div>
  </section>
</div>
<script>
(function(){
  var btn = document.getElementById('themeToggle');
  if (btn) {
    btn.addEventListener('click', function() {
      var html = document.documentElement;
      if (html.getAttribute('data-theme') === 'dark') {
        html.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
      } else {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
      }
    });
  }
})();
</script>
</body>
</html>
