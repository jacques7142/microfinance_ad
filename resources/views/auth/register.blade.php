<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription sociétaire — COOPEC-AD</title>
<script>!function(){var t=localStorage.getItem('theme');if(t==='dark'||(!t&&window.matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.setAttribute('data-theme','dark')}();</script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800;900&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --navy:#011f62; --navy-2:#0a3a8f; --gold:#e8a33d; --gold-2:#c97f1e; --muted:#a1b0d0;
  --bg:#f3f6fb; --surface:#ffffff; --ink:#101a2e; --line:#e6e9f2; --radius:18px;
  --shadow:0 24px 48px rgba(1,31,98,.12);
}
[data-theme="dark"]{
  --navy:#1e3a5f; --navy-2:#2d5aa0; --gold:#e8a33d; --gold-2:#c97f1e; --muted:#7e93b0;
  --bg:#0c1222; --surface:#161e30; --ink:#e2e8f0; --line:#1e2a45; --radius:18px;
  --shadow:0 24px 48px rgba(0,0,0,.4);
}
*{box-sizing:border-box;} body{margin:0;font-family:'Manrope',sans-serif;background:var(--bg);color:var(--ink);-webkit-font-smoothing:antialiased;}
h1,h2,h3,h4{font-family:'Sora',sans-serif;margin:0;}
.wrap{min-height:100vh;display:flex;}

/* ===== LEFT PANEL ===== */
.left{flex:1.15;background:linear-gradient(160deg,#011f62,#04275f);color:#fff;padding:48px 44px;display:flex;flex-direction:column;position:relative;overflow:hidden;}
.left::before{content:'';position:absolute;top:-30%;right:-20%;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(232,163,61,.10) 0%,transparent 70%);pointer-events:none;}
.left::after{content:'';position:absolute;bottom:-20%;left:-10%;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(232,163,61,.06) 0%,transparent 70%);pointer-events:none;}

.hero-brand{display:flex;align-items:center;gap:12px;position:relative;z-index:1;}
.hero-brand .mark{width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,var(--gold),var(--gold-2));display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:800;color:var(--navy);font-size:16px;box-shadow:0 4px 12px rgba(232,163,61,.35);}
.hero-brand .company{font-family:'Sora';font-weight:800;font-size:16px;letter-spacing:.02em;}
.hero-brand .badge{margin-left:auto;font-size:10px;text-transform:uppercase;letter-spacing:.06em;padding:5px 12px;border-radius:20px;background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.8);}

.hero-content{position:relative;z-index:1;flex:1;display:flex;flex-direction:column;justify-content:center;padding:20px 0;}
.hero-tagline{display:inline-flex;align-items:center;gap:6px;font-size:11px;text-transform:uppercase;letter-spacing:.12em;padding:6px 16px;border-radius:20px;background:rgba(232,163,61,.15);border:1px solid rgba(232,163,61,.25);color:var(--gold);width:fit-content;margin-bottom:20px;}
.hero-content h1{font-size:42px;line-height:1.05;max-width:540px;}
.hero-content h1 span{background:linear-gradient(135deg,var(--gold),#f0c060);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hero-desc{color:#b8c7e6;font-size:14px;margin-top:18px;max-width:460px;line-height:1.85;}

.offre{margin-top:32px;display:grid;grid-template-columns:repeat(2,1fr);gap:12px;max-width:520px;}
.offre-card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px 18px;transition:all .25s;cursor:default;}
.offre-card:hover{background:rgba(255,255,255,.10);transform:translateY(-2px);border-color:rgba(232,163,61,.25);}
.offre-card .icon{width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;}
.offre-card .icon svg{width:16px;height:16px;}
.offre-card h4{font-size:13px;font-weight:700;margin-bottom:3px;}
.offre-card p{font-size:11px;color:rgba(255,255,255,.6);margin:0;line-height:1.5;}

.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-top:36px;}
.kpi{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.09);border-radius:14px;padding:16px 14px;text-align:center;transition:all .25s;}
.kpi:hover{background:rgba(255,255,255,.10);transform:translateY(-2px);border-color:rgba(232,163,61,.2);}
.kpi .value{font-family:'Sora';font-size:24px;font-weight:800;letter-spacing:-.02em;}
.kpi .label{font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.65);margin-top:6px;}

.hero-footer{margin-top:28px;padding-top:20px;border-top:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:space-between;position:relative;z-index:1;}
.hero-footer .note{font-size:11px;color:rgba(255,255,255,.55);max-width:360px;line-height:1.6;}
.hero-footer .seal{display:flex;align-items:center;gap:8px;font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.45);}
.hero-footer .seal svg{width:18px;height:18px;flex-shrink:0;}

/* ===== RIGHT PANEL ===== */
.right{flex:.9;background:var(--bg);display:flex;align-items:center;justify-content:center;padding:40px;position:relative;}
.card{width:100%;max-width:480px;background:var(--surface);border-radius:20px;box-shadow:var(--shadow);padding:32px;}
.card h1{font-size:24px;line-height:1.2;margin-bottom:8px;}
.card .subtitle{color:var(--muted);font-size:14px;line-height:1.75;margin-bottom:24px;}
.row{display:flex;gap:12px;}
.row .field{flex:1;}
.field{margin-bottom:16px;}
.field label{display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;text-transform:uppercase;letter-spacing:.04em;}
.field label .optional{font-weight:400;text-transform:none;letter-spacing:0;}
.field input,.field select{width:100%;padding:14px 16px;border-radius:14px;border:1px solid var(--line);font-size:14px;background:var(--bg);transition:all .25s;color:var(--ink);}
.field input:focus,.field select:focus{outline:none;border-color:var(--navy-2);background:var(--surface);box-shadow:0 0 0 3px rgba(10,58,143,.10);}
.field input::placeholder{color:#b8c4dd;font-size:13px;}
.btn{width:100%;border:none;border-radius:14px;padding:13px 20px;font-weight:700;font-size:14px;cursor:pointer;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);transition:all .25s;display:flex;align-items:center;justify-content:center;gap:8px;}
.btn:hover{opacity:.92;transform:translateY(-1px);box-shadow:0 8px 20px rgba(232,163,61,.30);}
.alert{background:rgba(220,38,38,.08);color:#dc2626;padding:14px 16px;border-radius:14px;font-size:13px;margin-bottom:22px;border:1px solid rgba(220,38,38,.12);}
.info{background:rgba(232,163,61,.10);border:1px solid rgba(232,163,61,.20);border-radius:14px;padding:14px 16px;font-size:13px;color:var(--gold-2);display:flex;align-items:center;gap:10px;margin-top:18px;}
.info svg{width:18px;height:18px;flex:none;}
.footer-text{margin-top:20px;font-size:13px;text-align:center;color:var(--muted);}
.footer-text a{color:var(--navy);text-decoration:none;font-weight:700;transition:opacity .2s;}
.footer-text a:hover{opacity:.7;}

.upload-zone{border:2px dashed var(--line);border-radius:14px;padding:24px;text-align:center;cursor:pointer;transition:all .25s;background:var(--bg);position:relative;}
.upload-zone:hover,.upload-zone.dragover{border-color:var(--navy-2);background:rgba(10,58,143,.04);}
.upload-zone .icon{width:36px;height:36px;margin:0 auto 10px;background:var(--line);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--navy-2);}
.upload-zone .main-text{font-weight:600;font-size:14px;color:var(--ink);}
.upload-zone .sub-text{font-size:12px;color:var(--muted);margin-top:4px;}
.upload-zone .file-name{font-size:12px;color:var(--navy-2);font-weight:600;margin-top:8px;display:none;}
.upload-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;}

.signature-pad-wrap{border:2px solid var(--line);border-radius:14px;overflow:hidden;background:var(--surface);position:relative;touch-action:none;}
.signature-pad-wrap canvas{display:block;width:100%;height:160px;cursor:crosshair;}
.signature-pad-toolbar{display:flex;justify-content:space-between;align-items:center;padding:8px 14px;background:var(--bg);border-top:1px solid var(--line);}
.signature-pad-toolbar .sig-label{font-size:11px;color:var(--muted);font-weight:600;}
.signature-pad-toolbar button{background:none;border:1px solid var(--line);border-radius:8px;padding:6px 14px;font-size:11px;cursor:pointer;color:var(--muted);transition:all .15s;}
.signature-pad-toolbar button:hover{background:rgba(10,58,143,.06);color:var(--navy-2);border-color:var(--navy-2);}
.signature-pad-toolbar .sig-confirm{background:var(--navy);color:#fff;border-color:var(--navy);}
.signature-pad-toolbar .sig-confirm:hover{background:var(--navy-2);border-color:var(--navy-2);}

.theme-btn{position:absolute;top:20px;right:28px;background:var(--surface);border:1px solid var(--line);border-radius:12px;width:42px;height:42px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);z-index:10;transition:all .25s;box-shadow:0 2px 8px rgba(0,0,0,.04);}
.theme-btn:hover{border-color:var(--gold);color:var(--gold);}
[data-theme="dark"] .theme-btn .moon-icon{display:block !important;}
[data-theme="dark"] .theme-btn .sun-icon{display:none !important;}
:root:not([data-theme="dark"]) .theme-btn .sun-icon{display:block !important;}

@media(max-width:1060px){.wrap{flex-direction:column;}.left,.right{padding:32px;}.kpis{grid-template-columns:repeat(2,1fr);}.offre{grid-template-columns:1fr;}.hero-content h1{font-size:32px;}}
@media(max-width:700px){.hero-content h1{font-size:28px;}.hero-desc{max-width:none;}.kpis{grid-template-columns:1fr;}.card{padding:24px;}.hero-footer{flex-direction:column;align-items:flex-start;gap:12px;}.row{flex-direction:column;gap:0;}}
</style>
</head>
<body>
<div class="wrap">
  <section class="left">
    <div class="hero-brand">
      <div class="mark">CA</div>
      <div class="company">COOPEC-AD Togo</div>
      <div class="badge">SFD agréé</div>
    </div>

    <div class="hero-content">
      <div class="hero-tagline">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        Institution de microfinance
      </div>

      <h1>« Le vrai bonheur »,<br><span>géré simplement.</span></h1>
      <p class="hero-desc">
        COOPEC-AD est une coopérative d'épargne et de crédit engagée pour le développement.
        Nous accompagnons nos sociétaires avec des solutions financières accessibles, transparentes
        et adaptées aux réalités locales.
      </p>

      <div class="offre">
        <div class="offre-card">
          <div class="icon" style="background:rgba(232,163,61,.18);">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          </div>
          <h4>Épargne DAV / DAT</h4>
          <p>Comptes d'épargne flexibles et dépôts à terme rémunérés</p>
        </div>
        <div class="offre-card">
          <div class="icon" style="background:rgba(232,163,61,.18);">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <h4>Crédits</h4>
          <p>Financements adaptés aux besoins des sociétaires</p>
        </div>
        <div class="offre-card">
          <div class="icon" style="background:rgba(232,163,61,.18);">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
          </div>
          <h4>Tontine LOGOKU</h4>
          <p>Épargne collective et solidaire pour projets communs</p>
        </div>
        <div class="offre-card">
          <div class="icon" style="background:rgba(232,163,61,.18);">
            <svg viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
          </div>
          <h4>Multi-agences</h4>
          <p>Réseau d'agences interconnectées pour vous servir</p>
        </div>
      </div>

      <div class="kpis">
        <div class="kpi"><div class="value">{{ number_format($agenceCount, 0, ',', ' ') }}</div><div class="label">agences</div></div>
        <div class="kpi"><div class="value">{{ number_format($societaireCount, 0, ',', ' ') }}</div><div class="label">sociétaires</div></div>
        <div class="kpi"><div class="value">{{ number_format($utilisateurCount, 0, ',', ' ') }}</div><div class="label">collaborateurs</div></div>
        <div class="kpi"><div class="value">{{ number_format($profilCount, 0, ',', ' ') }}</div><div class="label">profils métiers</div></div>
      </div>
    </div>

    <div class="hero-footer">
      <p class="note">Institution de microfinance agréée SFD — Direction de la Microfinance, zone UEMOA.</p>
      <div class="seal">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        Données sécurisées
      </div>
    </div>
  </section>

  <section class="right">
    <button id="themeToggle" class="theme-btn" aria-label="Changer le thème">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sun-icon" style="display:none"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="moon-icon" style="display:none"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
    </button>

    <div class="card">
      <h1>Créer votre compte</h1>
      <p class="subtitle">Créez votre compte sociétaire en quelques clics. Après inscription, vous accéderez à votre espace personnel.</p>

      @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" id="registerForm">
        @csrf

        <div class="field">
          <label>Agence</label>
          <select name="agence_id" required>
            <option value="">Sélectionnez votre agence</option>
            @foreach($agences as $agence)
              <option value="{{ $agence->id }}" {{ old('agence_id') == $agence->id ? 'selected' : '' }}>{{ $agence->nom }}</option>
            @endforeach
          </select>
        </div>

        <div class="row">
          <div class="field">
            <label>Nom</label>
            <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex. KODJOVI" required>
          </div>
          <div class="field">
            <label>Prénom</label>
            <input type="text" name="prenom" value="{{ old('prenom') }}" placeholder="Ex. Yawo" required>
          </div>
        </div>

        <div class="field">
          <label>Numéro de téléphone</label>
          <input type="text" name="telephone" value="{{ old('telephone') }}" placeholder="Ex. 90 12 34 56 ou +228 90 12 34 56" required>
        </div>

        <div class="field">
          <label>Email <span class="optional">(facultatif)</span></label>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="Ex. yawo.kodjovi@email.com">
        </div>

        <div class="field">
          <label>Adresse</label>
          <input type="text" name="adresse" value="{{ old('adresse') }}" placeholder="Ex. Agoé, Lomé, Kara...">
        </div>

        <div class="field">
          <label>Pièce d'identité (CNI, passeport, permis)</label>
          <div class="upload-zone" id="uploadZone">
            <div class="icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            </div>
            <div class="main-text">Cliquez ou glissez votre fichier ici</div>
            <div class="sub-text">PDF, JPG ou PNG — 5 Mo max</div>
            <div class="file-name" id="fileName"></div>
            <input type="file" name="piece_identite" accept=".pdf,.jpg,.jpeg,.png" id="fileInput" required>
          </div>
        </div>

        <div class="field">
          <label>Votre signature électronique</label>
          <p style="font-size:11px;color:var(--muted);margin:0 0 8px;">Tracez votre signature dans le cadre ci-dessous</p>
          <div class="signature-pad-wrap">
            <canvas id="sigCanvas" width="600" height="160"></canvas>
            <div class="signature-pad-toolbar">
              <span class="sig-label">✍ Signature</span>
              <div style="display:flex;gap:6px;">
                <button type="button" id="sigClear">Effacer</button>
                <button type="button" id="sigConfirm" class="sig-confirm">Confirmer ✓</button>
              </div>
            </div>
          </div>
          <input type="hidden" name="signature" id="sigInput" value="">
          <p id="sigStatus" style="font-size:11px;color:#dc2626;margin:6px 0 0;">Signature requise</p>
        </div>

        <div class="row">
          <div class="field">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="Min. 8 caractères" required>
          </div>
          <div class="field">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" placeholder="Répétez le mot de passe" required>
          </div>
        </div>

        <button class="btn" type="submit" id="submitBtn">Créer mon compte</button>
      </form>

      <div class="info">
        <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1C4.686 1 2 3.686 2 7v2c0 1.795.995 3.354 2.492 4.27A1.75 1.75 0 0 0 6 15h4a1.75 1.75 0 0 0 1.508-.73C13.005 12.354 14 10.795 14 9V7c0-3.314-2.686-6-6-6Zm0 2c2.21 0 4 1.79 4 4v2c0 1.21-.654 2.289-1.664 2.884A.25.25 0 0 1 10.25 11H5.75a.25.25 0 0 1-.086-.016C3.654 10.289 3 9.21 3 8V7c0-2.21 1.79-4 4-4Zm-.75 4.5h1.5V8H7.25V5.5Zm0 3h1.5V12H7.25V8.5Z" fill="#8A5A12"/></svg>
        Votre inscription est sécurisée et vos données sont protégées.
      </div>
      <p class="footer-text">Vous avez déjà un compte ? <a href="{{ route('login.form') }}">Se connecter</a></p>
    </div>
  </section>
</div>

<script>
(function(){
  // --- Theme toggle ---
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

  // --- File upload drag & drop ---
  var zone = document.getElementById('uploadZone');
  var fileInput = document.getElementById('fileInput');
  var fileName = document.getElementById('fileName');

  zone.addEventListener('dragover', function(e) { e.preventDefault(); zone.classList.add('dragover'); });
  zone.addEventListener('dragleave', function() { zone.classList.remove('dragover'); });
  zone.addEventListener('drop', function(e) { e.preventDefault(); zone.classList.remove('dragover'); if (e.dataTransfer.files.length) fileInput.files = e.dataTransfer.files; updateFileName(); });

  fileInput.addEventListener('change', updateFileName);
  function updateFileName() {
    if (fileInput.files.length > 0) {
      fileName.textContent = fileInput.files[0].name + ' (' + (fileInput.files[0].size / 1024 / 1024).toFixed(1) + ' Mo)';
      fileName.style.display = 'block';
    } else {
      fileName.style.display = 'none';
    }
  }

  // --- Signature Pad ---
  var canvas = document.getElementById('sigCanvas');
  var ctx = canvas.getContext('2d');
  var sigInput = document.getElementById('sigInput');
  var sigStatus = document.getElementById('sigStatus');
  var sigConfirmBtn = document.getElementById('sigConfirm');
  var sigClearBtn = document.getElementById('sigClear');
  var submitBtn = document.getElementById('submitBtn');

  var drawing = false;
  var confirmed = false;
  var hasDrawn = false;

  function resizeCanvas() {
    var rect = canvas.parentElement.getBoundingClientRect();
    var w = rect.width - 4;
    var aspect = 600 / 160;
    var h = w / aspect;
    canvas.style.width = w + 'px';
    canvas.style.height = h + 'px';
  }
  resizeCanvas();
  window.addEventListener('resize', resizeCanvas);

  function getPos(e) {
    var rect = canvas.getBoundingClientRect();
    var x, y;
    if (e.touches) {
      x = (e.touches[0].clientX - rect.left) * (canvas.width / rect.width);
      y = (e.touches[0].clientY - rect.top) * (canvas.height / rect.height);
    } else {
      x = (e.clientX - rect.left) * (canvas.width / rect.width);
      y = (e.clientY - rect.top) * (canvas.height / rect.height);
    }
    return { x: Math.min(Math.max(x, 0), canvas.width), y: Math.min(Math.max(y, 0), canvas.height) };
  }

  function startDraw(e) {
    if (confirmed) return;
    e.preventDefault();
    drawing = true;
    hasDrawn = true;
    var pos = getPos(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
  }

  function draw(e) {
    if (!drawing || confirmed) return;
    e.preventDefault();
    var pos = getPos(e);
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = '#011f62';
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
  }

  function endDraw(e) {
    drawing = false;
    ctx.beginPath();
  }

  canvas.addEventListener('mousedown', startDraw);
  canvas.addEventListener('mousemove', draw);
  canvas.addEventListener('mouseup', endDraw);
  canvas.addEventListener('mouseleave', endDraw);
  canvas.addEventListener('touchstart', startDraw, { passive: false });
  canvas.addEventListener('touchmove', draw, { passive: false });
  canvas.addEventListener('touchend', endDraw, { passive: false });

  sigClearBtn.addEventListener('click', function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasDrawn = false;
    confirmed = false;
    sigInput.value = '';
    sigStatus.textContent = 'Signature requise';
    sigStatus.style.color = '#dc2626';
    sigConfirmBtn.textContent = 'Confirmer ✓';
    sigConfirmBtn.className = 'sig-confirm';
  });

  sigConfirmBtn.addEventListener('click', function() {
    if (!hasDrawn) {
      sigStatus.textContent = 'Veuillez d\'abord tracer votre signature';
      sigStatus.style.color = '#dc2626';
      return;
    }
    sigInput.value = canvas.toDataURL('image/png');
    confirmed = true;
    sigStatus.textContent = '✓ Signature confirmée';
    sigStatus.style.color = '#16a34a';
    sigConfirmBtn.textContent = '✔ Confirmé';
    sigConfirmBtn.className = '';
    sigConfirmBtn.style.borderColor = '#16a34a';
    sigConfirmBtn.style.color = '#16a34a';
  });

  // --- Form submit validation ---
  document.getElementById('registerForm').addEventListener('submit', function(e) {
    if (!sigInput.value) {
      e.preventDefault();
      sigStatus.textContent = 'Veuillez tracer et confirmer votre signature avant de soumettre';
      sigStatus.style.color = '#dc2626';
    }
  });
})();
</script>
</body>
</html>
