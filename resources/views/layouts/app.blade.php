@php $cpNs = 'u' . auth()->id(); @endphp
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Tableau de bord') — COOPEC-AD</title>
<script>!function(){var P='coopec.{{ $cpNs }}.',h=document.documentElement;function g(k,l,f){var v=localStorage.getItem(P+k);return v===null?localStorage.getItem(l)||f:v}var t=g('theme','theme','auto');if(t==='dark'||((t==='auto'||!t)&&window.matchMedia('(prefers-color-scheme: dark)').matches))h.setAttribute('data-theme','dark');var z={petite:.9,normale:1,grande:1.1,'tres-grande':1.25};h.style.zoom=(z[g('fontSize','coopec.fontSize','normale')]||1);h.setAttribute('data-densite',g('densite','coopec.densite','confortable'));h.setAttribute('lang',g('lang','coopec.lang','fr'))}();</script>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
:root{
  --navy:#011f62; --navy-2:#0a3a8f; --gold:#e8a33d; --gold-2:#c97f1e;
  --green:#1e8a5f; --green-bg:#e8f6ef; --red:#c4453b; --red-bg:#fbeae8; --amber-bg:#fdf3e2;
  --bg:#f4f6fb; --surface:#fff; --ink:#101a2e; --muted:#5c6479; --line:#e6e9f2; --radius:14px;
  --shadow:0 1px 2px rgba(16,26,46,.04), 0 8px 24px -12px rgba(1,31,98,.18);
  --sidebar-w:264px; --content-max:1320px;
  --bg-overlay:linear-gradient(180deg,rgba(246,248,253,.96) 0%,rgba(240,244,252,.88) 45%,rgba(246,248,253,.94) 100%);
}
html[data-densite="compacte"]{--sidebar-w:220px;--content-max:1120px;}
html[data-densite="large"]{--sidebar-w:310px;--content-max:1600px;}
[data-theme="dark"]{
  --navy:#1e3a5f; --navy-2:#2d5aa0; --gold:#e8a33d; --gold-2:#c97f1e;
  --green:#22c55e; --green-bg:#0a2e1a; --red:#ef4444; --red-bg:#2d1414; --amber-bg:#2d220f;
  --bg:#0c1222; --surface:#161e30; --ink:#e2e8f0; --muted:#7e93b0; --line:#1e2a45; --radius:14px;
  --shadow:0 1px 2px rgba(0,0,0,.2), 0 8px 24px -12px rgba(0,0,0,.5);
  --bg-overlay:linear-gradient(180deg,rgba(12,18,34,.96) 0%,rgba(12,18,34,.90) 45%,rgba(12,18,34,.94) 100%);
}
*{box-sizing:border-box;} body{margin:0;font-family:'Manrope',sans-serif;background:var(--bg);color:var(--ink);}
h1,h2,h3,h4{font-family:'Sora',sans-serif;margin:0;}
@keyframes coopecKenburns{from{transform:scale(1) translate3d(0,0,0);}to{transform:scale(1.09) translate3d(-1.2%,-1.2%,0);}}
body::before{content:"";position:fixed;inset:-40px;z-index:-1;background:url('{{ asset('images/coopec-bg.jpg') }}') center/cover no-repeat;animation:coopecKenburns 42s ease-in-out infinite alternate;will-change:transform;}
body::after{content:"";position:fixed;inset:0;z-index:0;background:var(--bg-overlay);pointer-events:none;}
@media (prefers-reduced-motion: reduce){body::before{animation:none;}}
.app{display:flex;min-height:100vh;position:relative;z-index:1;}
.sidebar{width:var(--sidebar-w);flex:none;background:var(--navy);color:#fff;position:sticky;top:0;height:100vh;display:flex;flex-direction:column;}
.brand{display:flex;align-items:center;gap:10px;padding:22px 20px 16px;}
.brand .mark{width:50px;height:50px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:800;color:var(--navy);font-size:14px;background:#fff;padding:2px;}
.brand .mark img{width:100%;height:100%;object-fit:contain;border-radius:4px;}
.brand .word{font-family:'Sora';font-weight:800;font-size:15.5px;}
.brand .word span{display:block;font-family:'Manrope';font-weight:600;font-size:10px;color:#b9c6ea;text-transform:uppercase;}
.nav-group{padding:6px 12px;flex:1;overflow-y:auto;min-height:0;}
.nav-label{font-size:10.5px;text-transform:uppercase;letter-spacing:.08em;color:#8fa0cf;font-weight:700;margin:16px 10px 6px;}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:9px;color:#dbe3f7;font-size:13.5px;font-weight:600;text-decoration:none;margin-bottom:2px;transition:all .15s;}
.nav-item:hover{background:rgba(255,255,255,.08);color:#fff;}
.nav-item.active{background:rgba(232,163,61,.16);color:#fff;}
.nav-item svg{flex:none;opacity:.7;}
.nav-item.active svg,.nav-item:hover svg{opacity:1;}
.sidebar-foot{margin-top:auto;padding:16px 20px;border-top:1px solid rgba(255,255,255,.1);font-size:11px;color:#8fa0cf;}
.sidebar-foot form button{background:rgba(232,163,61,.12);border:none;color:var(--gold);font-size:12px;cursor:pointer;padding:8px 16px;font-weight:700;border-radius:8px;width:100%;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:6px;letter-spacing:.02em;}
.sidebar-foot form button:hover{background:rgba(232,163,61,.25);transform:translateY(-1px);}
.main{flex:1;min-width:0;}
.topbar{height:62px;background:var(--surface);border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 24px;}
.topbar .title{font-family:'Sora';font-weight:700;font-size:15px;}
.topbar .sub{font-size:12px;color:var(--muted);}
.profile-menu{display:flex;align-items:center;gap:12px;cursor:pointer;position:relative;}
.avatar{width:40px;height:40px;border-radius:50%;background:var(--navy-2);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;font-family:'Sora';overflow:hidden;border:2px solid var(--gold);}
.avatar img{width:100%;height:100%;object-fit:cover;}
.profile-dropdown{display:none;position:absolute;top:100%;right:0;background:var(--surface);border:1px solid var(--line);border-radius:10px;box-shadow:var(--shadow);min-width:220px;z-index:1000;margin-top:8px;}
.profile-dropdown.active{display:block;}
.profile-dropdown-item{padding:12px 16px;border-bottom:1px solid var(--line);text-decoration:none;color:var(--ink);font-size:13px;display:flex;align-items:center;gap:10px;transition:all 0.2s;}
.profile-dropdown-item:last-child{border-bottom:none;}
.profile-dropdown-item:hover{background:var(--bg);}
.profile-dropdown-item.logout{color:var(--red);font-weight:700;}
.content{padding:24px 28px 60px;max-width:var(--content-max);margin:0 auto;}
.grid{display:grid;gap:16px;} .g4{grid-template-columns:repeat(4,1fr);} .g3{grid-template-columns:repeat(3,1fr);} .g2{grid-template-columns:repeat(2,1fr);}
@media(max-width:1000px){.g4,.g3{grid-template-columns:repeat(2,1fr);}}
.card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);}
.card-pad{padding:18px 20px;} .card-head{display:flex;align-items:center;justify-content:space-between;padding:15px 20px;border-bottom:1px solid var(--line);}
.card-head h3{font-size:14px;}
.stat .lbl{font-size:11.5px;color:var(--muted);font-weight:600;text-transform:uppercase;}
.stat .val{font-family:'Sora';font-size:22px;font-weight:800;margin-top:6px;color:var(--navy);}
.btn{border:none;border-radius:9px;padding:9px 15px;font-weight:700;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all .18s cubic-bezier(.4,0,.2,1);position:relative;font-family:'Manrope',sans-serif;}
.btn:hover{transform:translateY(-1px);}
.btn:active{transform:translateY(0) scale(.98);}
.btn:focus-visible{outline:2px solid var(--gold);outline-offset:2px;}
.btn-gold{background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);}
.btn-gold:hover{box-shadow:0 6px 18px rgba(232,163,61,.4);}
.btn-navy{background:var(--navy);color:#fff;}
.btn-navy:hover{background:var(--navy-2);box-shadow:0 6px 18px rgba(1,31,98,.25);}
.btn-ghost{background:var(--surface);border:1px solid var(--line);color:var(--ink);}
.btn-ghost:hover{background:var(--bg);border-color:#cdd3e1;}
.btn-danger{background:var(--red-bg);color:var(--red);}
.btn-danger:hover{background:var(--red);color:#fff;}
.btn-success{background:var(--green);color:#fff;}
.btn-success:hover{box-shadow:0 6px 18px rgba(30,138,95,.35);}
.btn-sm{padding:6px 11px;font-size:12px;}
.btn-lg{padding:12px 20px;font-size:14px;}
.btn-block{width:100%;justify-content:center;}
.badge{display:inline-flex;padding:3px 9px;border-radius:100px;font-size:11px;font-weight:700;}
.b-green{background:var(--green-bg);color:var(--green);} .b-red{background:var(--red-bg);color:var(--red);}
.b-amber{background:var(--amber-bg);color:var(--gold-2);} .b-navy{background:#e8edfb;color:var(--navy-2);}
table{width:100%;border-collapse:collapse;font-size:13px;}
th{text-align:left;font-size:11px;text-transform:uppercase;color:var(--muted);padding:10px 20px;border-bottom:1px solid var(--line);}
td{padding:11px 20px;border-bottom:1px solid var(--line);}
.field{margin-bottom:14px;} .field label{display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:5px;}
.field input,.field select,.field textarea{width:100%;padding:9px 11px;border-radius:8px;border:1px solid var(--line);font-family:inherit;font-size:13px;}
.alert{padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px;}
.alert-success{background:var(--green-bg);color:var(--green);} .alert-error{background:var(--red-bg);color:var(--red);}
.section-title{margin:26px 0 12px;} .section-title h2{font-size:17px;}
.theme-btn{background:none;border:1px solid var(--line);border-radius:8px;width:36px;height:36px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:all .15s;flex:none;}
.theme-btn:hover{background:var(--bg);color:var(--ink);}
.theme-btn .sun-icon,.theme-btn .moon-icon{display:none;}
[data-theme="dark"] .theme-btn .moon-icon{display:block;}
[data-theme="dark"] .theme-btn .sun-icon{display:none;}
:root:not([data-theme="dark"]) .theme-btn .sun-icon{display:block;}
</style>
@stack('styles')
</head>
<body>
<div class="app">
  <aside class="sidebar">
    <div class="brand">
      <div class="mark">
        <img src="{{ asset('images/logo-cad-icon.png') }}" alt="Logo COOPEC-AD" title="COOPEC-AD">
      </div>
      <div class="word">COOPEC-AD<span>Plateforme coopérative</span></div>
    </div>
    <div class="nav-group">
      <div class="nav-label">Général</div>
      <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <x-icon name="dashboard" size="18"/> Tableau de bord
      </a>
      <a href="{{ route('societaires.index') }}" class="nav-item {{ request()->routeIs('societaires.*') ? 'active' : '' }}">
        <x-icon name="users" size="18"/> Sociétaires
      </a>
      <a href="{{ route('credits.index') }}" class="nav-item {{ request()->routeIs('credits.*') ? 'active' : '' }}">
        <x-icon name="credit" size="18"/> Crédits
      </a>

      @php
        $messagesNonLus = App\Models\Message::where('expediteur', 'societaire')
            ->where('lu', false)
            ->when(!auth()->user()->hasRole('administrateur'), fn ($q) => $q->whereHas('societaire', fn ($sq) => $sq->where('agence_id', auth()->user()->agence_id)))
            ->count();
      @endphp
      <a href="{{ route('messages.index') }}" class="nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
        <x-icon name="chat" size="18"/> Messagerie
        @if($messagesNonLus > 0)<span style="margin-left:auto;background:var(--gold);color:var(--navy);font-size:10.5px;font-weight:800;min-width:18px;height:18px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;padding:0 5px;">{{ $messagesNonLus }}</span>@endif
      </a>
      <a href="{{ route('parametres.index') }}" class="nav-item {{ request()->routeIs('parametres.*') ? 'active' : '' }}">
        <x-icon name="settings" size="18"/> Paramètres
      </a>

      @if(auth()->user()->hasRole('caissier'))
        <div class="nav-label">Guichet</div>
        <a href="{{ route('epargne.index') }}" class="nav-item {{ request()->routeIs('epargne.*') ? 'active' : '' }}">
          <x-icon name="wallet" size="18"/> Dépôts / retraits
        </a>
      @endif

      @if(auth()->user()->hasRole('agent_promotion'))
        <div class="nav-label">Tontine LOGOKU</div>
        <a href="{{ route('tontine.index') }}" class="nav-item {{ request()->routeIs('tontine.*') ? 'active' : '' }}">
          <x-icon name="tontine" size="18"/> Ma tournée
        </a>
      @endif

      @if(auth()->user()->aUnRole(['agent_credit','gerant','administrateur']))
        <div class="nav-label">Gestion</div>
        <a href="{{ route('societaires.create') }}" class="nav-item {{ request()->routeIs('societaires.create') ? 'active' : '' }}">
          <x-icon name="plus" size="18"/> Nouveau sociétaire
        </a>
        <a href="{{ route('credits.create') }}" class="nav-item {{ request()->routeIs('credits.create') ? 'active' : '' }}">
          <x-icon name="credit" size="18"/> Nouveau crédit
        </a>
      @endif

      @if(auth()->user()->aUnRole(['comptable','gerant','administrateur']))
        <div class="nav-label">Reporting</div>
        <a href="{{ route('rapports.index') }}" class="nav-item {{ request()->routeIs('rapports.*') ? 'active' : '' }}">
          <x-icon name="chart" size="18"/> Rapports
        </a>
      @endif

      @if(auth()->user()->hasRole('administrateur'))
        <div class="nav-label">Administration</div>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
          <x-icon name="user-check" size="18"/> Utilisateurs
        </a>
        <a href="{{ route('admin.agences.index') }}" class="nav-item {{ request()->routeIs('admin.agences.*') ? 'active' : '' }}">
          <x-icon name="building" size="18"/> Agences
        </a>
        <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
          <x-icon name="shield" size="18"/> Rôles & Permissions
        </a>
      @endif
    </div>
    <div class="sidebar-foot">
      {{ auth()->user()->nomComplet() }} — {{ implode(', ', array_map(fn ($r) => ucfirst(str_replace('_',' ', $r)), auth()->user()->rolesAttribues())) }}<br>
      <form method="POST" action="{{ route('logout') }}" style="margin-top:8px;">@csrf<button type="submit"><x-icon name="logout" size="15"/> Se déconnecter</button></form>
    </div>
  </aside>
  <div class="main">
    <div class="topbar">
      <div><div class="title">@yield('title')</div><div class="sub">{{ auth()->user()->agence->nom ?? 'Toutes agences' }}</div></div>
      <div style="display:flex;align-items:center;gap:8px;">
        <button id="themeToggle" class="theme-btn" aria-label="Changer le thème">
          <x-icon name="sun" size="18" class="sun-icon"/>
          <x-icon name="moon" size="18" class="moon-icon"/>
        </button>
      <div class="profile-menu" onclick="toggleProfileMenu(event)">
        <div class="avatar">
          @if(auth()->user()->photo_profil)
            <img src="{{ asset('storage/' . auth()->user()->photo_profil) }}" alt="Photo profil">
          @else
            {{ strtoupper(substr(auth()->user()->prenom,0,1).substr(auth()->user()->nom,0,1)) }}
          @endif
        </div>
        <div style="font-size: 13px;">
          <div style="font-weight: 600;">{{ auth()->user()->nomComplet() }}</div>
          <div style="font-size: 11px; color: var(--muted);">{{ implode(', ', array_map(fn ($r) => ucfirst(str_replace('_', ' ', $r)), auth()->user()->rolesAttribues())) }}</div>
        </div>
        <div class="profile-dropdown" id="profileDropdown">
          <a href="{{ route('profil.show') }}" class="profile-dropdown-item"><x-icon name="profile" size="16"/> Mon profil</a>
          <a href="{{ route('profil.edit') }}" class="profile-dropdown-item"><x-icon name="edit" size="16"/> Modifier le profil</a>
          <a href="{{ route('parametres.index') }}" class="profile-dropdown-item"><x-icon name="settings" size="16"/> Paramètres</a>
          <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="profile-dropdown-item logout" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; padding: 12px 16px; display: flex; align-items: center; gap: 8px;"><x-icon name="logout" size="16"/> Se déconnecter</button>
          </form>
        </div>
      </div>
      </div>
    </div>
    <div class="content">
      @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
      @if($errors->any())
        <div class="alert alert-error">
          @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
      @endif
      @yield('content')
    </div>
  </div>
</div>
@vite(['resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@stack('scripts')
<script src="{{ asset('js/i18n.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
function toggleProfileMenu(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    const profileMenu = event.target.closest('.profile-menu');
    if (!profileMenu) {
        dropdown.classList.remove('active');
    }
});

document.getElementById('themeToggle').addEventListener('click', function() {
    var html = document.documentElement;
    var key = 'coopec.{{ $cpNs }}.theme';
    if (html.getAttribute('data-theme') === 'dark') {
        html.removeAttribute('data-theme');
        localStorage.setItem(key, 'light');
    } else {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem(key, 'dark');
    }
});
</script>
</body>
</html>
