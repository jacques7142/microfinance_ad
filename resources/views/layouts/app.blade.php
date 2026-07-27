<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Tableau de bord') — COOPEC-AD</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
:root{
  --navy:#011f62; --navy-2:#0a3a8f; --gold:#e8a33d; --gold-2:#c97f1e;
  --green:#1e8a5f; --green-bg:#e8f6ef; --red:#c4453b; --red-bg:#fbeae8; --amber-bg:#fdf3e2;
  --bg:#f4f6fb; --surface:#fff; --ink:#101a2e; --muted:#5c6479; --line:#e6e9f2; --radius:14px;
  --shadow:0 1px 2px rgba(16,26,46,.04), 0 8px 24px -12px rgba(1,31,98,.18);
}
*{box-sizing:border-box;} body{margin:0;font-family:'Manrope',sans-serif;background:var(--bg);color:var(--ink);}
h1,h2,h3,h4{font-family:'Sora',sans-serif;margin:0;}
.app{display:flex;min-height:100vh;}
.sidebar{width:264px;flex:none;background:var(--navy);color:#fff;position:sticky;top:0;height:100vh;display:flex;flex-direction:column;}
.brand{display:flex;align-items:center;gap:10px;padding:22px 20px 16px;}
.brand .mark{width:50px;height:50px;border-radius:6px;display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:800;color:var(--navy);font-size:14px;background:#fff;padding:2px;}
.brand .mark img{width:100%;height:100%;object-fit:contain;border-radius:4px;}
.brand .word{font-family:'Sora';font-weight:800;font-size:15.5px;}
.brand .word span{display:block;font-family:'Manrope';font-weight:600;font-size:10px;color:#b9c6ea;text-transform:uppercase;}
.nav-group{padding:6px 12px;}
.nav-label{font-size:10.5px;text-transform:uppercase;letter-spacing:.08em;color:#8fa0cf;font-weight:700;margin:16px 10px 6px;}
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:9px;color:#dbe3f7;font-size:13.5px;font-weight:600;text-decoration:none;margin-bottom:2px;}
.nav-item:hover{background:rgba(255,255,255,.06);}
.nav-item.active{background:rgba(232,163,61,.16);color:#fff;}
.sidebar-foot{margin-top:auto;padding:16px 20px;border-top:1px solid rgba(255,255,255,.1);font-size:11px;color:#8fa0cf;}
.sidebar-foot form button{background:none;border:none;color:#dbe3f7;font-size:12px;cursor:pointer;padding:0;font-weight:700;}
.main{flex:1;min-width:0;}
.topbar{height:62px;background:#fff;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 24px;}
.topbar .title{font-family:'Sora';font-weight:700;font-size:15px;}
.topbar .sub{font-size:12px;color:var(--muted);}
.profile-menu{display:flex;align-items:center;gap:12px;cursor:pointer;position:relative;}
.avatar{width:40px;height:40px;border-radius:50%;background:var(--navy-2);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;font-family:'Sora';overflow:hidden;border:2px solid var(--gold);}
.avatar img{width:100%;height:100%;object-fit:cover;}
.profile-dropdown{display:none;position:absolute;top:100%;right:0;background:#fff;border:1px solid var(--line);border-radius:10px;box-shadow:var(--shadow);min-width:220px;z-index:1000;margin-top:8px;}
.profile-dropdown.active{display:block;}
.profile-dropdown-item{padding:12px 16px;border-bottom:1px solid var(--line);text-decoration:none;color:var(--ink);font-size:13px;display:block;transition:all 0.2s;}
.profile-dropdown-item:last-child{border-bottom:none;}
.profile-dropdown-item:hover{background:var(--bg);}
.profile-dropdown-item.logout{color:var(--red);font-weight:700;}
.content{padding:24px 28px 60px;max-width:1320px;margin:0 auto;}
.grid{display:grid;gap:16px;} .g4{grid-template-columns:repeat(4,1fr);} .g3{grid-template-columns:repeat(3,1fr);} .g2{grid-template-columns:repeat(2,1fr);}
@media(max-width:1000px){.g4,.g3{grid-template-columns:repeat(2,1fr);}}
.card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);}
.card-pad{padding:18px 20px;} .card-head{display:flex;align-items:center;justify-content:space-between;padding:15px 20px;border-bottom:1px solid var(--line);}
.card-head h3{font-size:14px;}
.stat .lbl{font-size:11.5px;color:var(--muted);font-weight:600;text-transform:uppercase;}
.stat .val{font-family:'Sora';font-size:22px;font-weight:800;margin-top:6px;color:var(--navy);}
.btn{border:none;border-radius:9px;padding:9px 15px;font-weight:700;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;text-decoration:none;}
.btn-gold{background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);}
.btn-navy{background:var(--navy);color:#fff;} .btn-ghost{background:#fff;border:1px solid var(--line);color:var(--ink);}
.btn-danger{background:var(--red-bg);color:var(--red);} .btn-sm{padding:6px 11px;font-size:12px;}
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
</style>
@stack('styles')
</head>
<body>
<div class="app">
  <aside class="sidebar">
    <div class="brand">
      <div class="mark">
        <img src="{{ asset('images/logo.svg') }}" alt="Logo COOPEC-AD" title="COOPEC-AD">
      </div>
      <div class="word">COOPEC-AD<span>Plateforme coopérative</span></div>
    </div>
    <div class="nav-group">
      <div class="nav-label">Général</div>
      <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">Tableau de bord</a>
      <a href="{{ route('societaires.index') }}" class="nav-item {{ request()->routeIs('societaires.*') ? 'active' : '' }}">Sociétaires</a>
      <a href="{{ route('credits.index') }}" class="nav-item {{ request()->routeIs('credits.*') ? 'active' : '' }}">Crédits</a>

      @if(auth()->user()->role === 'caissier')
        <div class="nav-label">Guichet</div>
        <a href="{{ route('epargne.index') }}" class="nav-item {{ request()->routeIs('epargne.*') ? 'active' : '' }}">Dépôts / retraits</a>
      @endif

      @if(auth()->user()->role === 'agent_promotion')
        <div class="nav-label">Tontine LOGOKU</div>
        <a href="{{ route('tontine.index') }}" class="nav-item {{ request()->routeIs('tontine.*') ? 'active' : '' }}">Ma tournée</a>
      @endif

      @if(in_array(auth()->user()->role, ['comptable','gerant','administrateur']))
        <div class="nav-label">Reporting</div>
        <a href="{{ route('rapports.index') }}" class="nav-item {{ request()->routeIs('rapports.*') ? 'active' : '' }}">Rapports</a>
      @endif

      @if(auth()->user()->role === 'administrateur')
        <div class="nav-label">Administration</div>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Utilisateurs</a>
        <a href="{{ route('admin.agences.index') }}" class="nav-item {{ request()->routeIs('admin.agences.*') ? 'active' : '' }}">Agences</a>
      @endif
    </div>
    <div class="sidebar-foot">
      {{ auth()->user()->nomComplet() }} — {{ ucfirst(str_replace('_',' ', auth()->user()->role)) }}<br>
      <form method="POST" action="{{ route('logout') }}" style="margin-top:6px;">@csrf<button type="submit">Se déconnecter</button></form>
    </div>
  </aside>
  <div class="main">
    <div class="topbar">
      <div><div class="title">@yield('title')</div><div class="sub">{{ auth()->user()->agence->nom ?? 'Toutes agences' }}</div></div>
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
          <div style="font-size: 11px; color: var(--muted);">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
        </div>
        <div class="profile-dropdown" id="profileDropdown">
          <a href="{{ route('profil.show') }}" class="profile-dropdown-item">👤 Mon profil</a>
          <a href="{{ route('profil.edit') }}" class="profile-dropdown-item">✏️ Modifier le profil</a>
          <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="profile-dropdown-item logout" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; padding: 12px 16px;">🚪 Se déconnecter</button>
          </form>
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
@stack('scripts')
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
</script>
</body>
</html>
