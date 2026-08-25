@extends(Auth::guard('societaire')->check() ? 'layouts.societaire' : 'layouts.app')
@section('title', 'Paramètres')
@section('content')
@php $cpNs = ($authType === 'societaire' ? 's' : 'u') . $utilisateur->id; @endphp

<style>
.param-wrap{max-width:900px;margin:0 auto;}
.param-head{display:flex;align-items:center;gap:14px;margin-bottom:22px;}
.param-head .ico{width:48px;height:48px;border-radius:12px;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;flex:none;}
.param-head h2{font-size:20px;}
.param-head p{margin:2px 0 0;font-size:12.5px;color:var(--muted);}
.param-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);margin-bottom:18px;overflow:hidden;}
.param-card-head{display:flex;align-items:center;gap:10px;padding:15px 20px;border-bottom:1px solid var(--line);}
.param-card-head .lbl{font-size:14px;font-family:'Sora',sans-serif;font-weight:700;}
.param-card-head .desc{font-size:12px;color:var(--muted);}
.param-card-body{padding:16px 20px;}
.param-row{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:12px 0;border-bottom:1px solid var(--line);}
.param-row:last-child{border-bottom:none;}
.param-row .tt{font-size:13px;font-weight:700;}
.param-row .dd{font-size:12px;color:var(--muted);margin-top:2px;}
.param-options{display:flex;gap:8px;flex-wrap:wrap;}
.param-option{border:1px solid var(--line);background:var(--bg);border-radius:9px;padding:8px 13px;font-size:12.5px;font-weight:700;color:var(--muted);cursor:pointer;transition:all .15s;font-family:'Manrope',sans-serif;}
.param-option:hover{border-color:var(--gold);color:var(--ink);}
.param-option.active{background:linear-gradient(135deg,var(--gold),var(--gold-2));border-color:transparent;color:var(--navy);}
.param-option .mini{display:block;font-size:10px;font-weight:600;opacity:.75;margin-top:1px;}
.param-note{font-size:11.5px;color:var(--muted);margin-top:8px;}
.param-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:700;}
.b-green{background:var(--green-bg);color:var(--green);} .b-amber{background:var(--amber-bg);color:var(--gold-2);}
.param-btn{border:none;border-radius:9px;padding:9px 15px;font-weight:700;font-size:12.5px;cursor:pointer;font-family:'Manrope',sans-serif;display:inline-flex;align-items:center;gap:6px;transition:all .15s;}
.param-btn-navy{background:var(--navy);color:#fff;} .param-btn-navy:hover{background:var(--navy-2);}
.param-btn-ghost{background:var(--surface);border:1px solid var(--line);color:var(--ink);}
.param-btn-danger{background:var(--red-bg);color:var(--red);}
.param-switch{position:relative;width:46px;height:26px;flex:none;}
.param-switch input{opacity:0;width:0;height:0;}
.param-switch .track{position:absolute;inset:0;background:var(--line);border-radius:999px;transition:background .2s;cursor:pointer;}
.param-switch .track::after{content:"";position:absolute;top:3px;left:3px;width:20px;height:20px;border-radius:50%;background:#fff;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.25);}
.param-switch input:checked + .track{background:var(--green);}
.param-switch input:checked + .track::after{transform:translateX(20px);}
.version-line{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:12px 0;border-bottom:1px solid var(--line);}
.version-line:last-of-type{border-bottom:none;}
.version-line .k{font-size:13px;font-weight:700;}
.version-line .v{font-size:12.5px;color:var(--muted);font-family:monospace;}
.about-line{display:flex;gap:10px;padding:10px 0;border-bottom:1px solid var(--line);align-items:flex-start;}
.about-line:last-child{border-bottom:none;}
.about-line .k{font-size:12.5px;color:var(--muted);min-width:130px;}
.about-line .v{font-size:12.5px;font-weight:600;}
</style>

<div class="param-wrap">
  <div class="param-head">
    <div class="ico"><x-icon name="settings" size="24"/></div>
    <div>
      <h2 data-i18n="title">Paramètres</h2>
      <p data-i18n="subtitle">Personnalisez la plateforme selon vos préférences.</p>
    </div>
  </div>

  <div class="param-card">
    <div class="param-card-head">
      <div class="lbl"><x-icon name="palette" size="16"/> <span data-i18n="appearance">Apparence</span></div>
    </div>
    <div class="param-card-body">

      <div class="param-row">
        <div>
          <div class="tt" data-i18n="themeMode">Mode d'affichage</div>
          <div class="dd" data-i18n="themeModeDesc">Choisissez le thème clair, sombre ou automatique.</div>
        </div>
        <div class="param-options" id="optTheme">
          <button type="button" class="param-option" data-value="auto"><span data-i18n="auto">Auto</span><span class="mini" data-i18n="autoMini">Suivre l'appareil</span></button>
          <button type="button" class="param-option" data-value="light"><span data-i18n="light">Clair</span><span class="mini">☀️</span></button>
          <button type="button" class="param-option" data-value="dark"><span data-i18n="dark">Sombre</span><span class="mini">🌙</span></button>
        </div>
      </div>

      <div class="param-row">
        <div>
          <div class="tt" data-i18n="fontSize">Taille de la police</div>
          <div class="dd" data-i18n="fontSizeDesc">Agrandissez ou réduisez le texte de toute l'interface.</div>
        </div>
        <div class="param-options" id="optFont">
          <button type="button" class="param-option" data-value="petite" data-i18n="small">Petite</button>
          <button type="button" class="param-option" data-value="normale" data-i18n="normal">Normale</button>
          <button type="button" class="param-option" data-value="grande" data-i18n="large">Grande</button>
          <button type="button" class="param-option" data-value="tres-grande" data-i18n="xl">Très grande</button>
        </div>
      </div>

      <div class="param-row">
        <div>
          <div class="tt" data-i18n="screenSize">Taille de l'interface / écran</div>
          <div class="dd" data-i18n="screenSizeDesc">Ajuste la largeur du menu latéral et de la zone de contenu.</div>
        </div>
        <div class="param-options" id="optDensite">
          <button type="button" class="param-option" data-value="compacte"><span data-i18n="compact">Compacte</span><span class="mini" data-i18n="compactMini">Menu réduit</span></button>
          <button type="button" class="param-option" data-value="confortable"><span data-i18n="comfortable">Confortable</span><span class="mini" data-i18n="comfortableMini">Recommandé</span></button>
          <button type="button" class="param-option" data-value="large"><span data-i18n="wide">Large</span><span class="mini" data-i18n="wideMini">Écran étendu</span></button>
        </div>
      </div>

    </div>
  </div>

  <div class="param-card">
    <div class="param-card-head">
      <div class="lbl"><x-icon name="chat" size="16"/> <span data-i18n="language">Langue</span></div>
    </div>
    <div class="param-card-body">
      <div class="param-row">
        <div>
          <div class="tt" data-i18n="languageChoice">Langue de l'interface</div>
          <div class="dd" data-i18n="languageDesc">La préférence est enregistrée sur cet appareil.</div>
        </div>
        <div class="param-options" id="optLang">
          <button type="button" class="param-option" data-value="fr">Français</button>
          <button type="button" class="param-option" data-value="en">English</button>
        </div>
      </div>
    </div>
  </div>

  <div class="param-card">
    <div class="param-card-head">
      <div class="lbl"><x-icon name="bell" size="16"/> <span data-i18n="notifications">Notifications</span></div>
    </div>
    <div class="param-card-body">
      <div class="param-row">
        <div>
          <div class="tt" data-i18n="notifPrefs">Préférences de notifications</div>
          <div class="dd" data-i18n="notifPrefsDesc">Autoriser les rappels et alertes sur cette plateforme.</div>
        </div>
        <label class="param-switch">
          <input type="checkbox" id="optNotifs" checked>
          <span class="track"></span>
        </label>
      </div>
    </div>
  </div>

  <div class="param-card">
    <div class="param-card-head">
      <div class="lbl"><x-icon name="refresh" size="16"/> <span data-i18n="updates">Mises à jour</span></div>
    </div>
    <div class="param-card-body">
      <div class="version-line">
        <div class="k" data-i18n="currentVersion">Version actuelle</div>
        <span class="param-badge b-green">v{{ $version }}</span>
      </div>
      <div class="version-line">
        <div class="k" data-i18n="framework">Framework</div>
        <div class="v">Laravel {{ app()->version() }}</div>
      </div>
      <div class="version-line">
        <div class="k" data-i18n="lastCheck">Dernière vérification</div>
        <div class="v" id="lastCheckLabel" data-i18n="never">Jamais</div>
      </div>
      <div style="padding-top:14px;">
        <button type="button" class="param-btn param-btn-navy" id="btnCheckUpdate"><x-icon name="refresh" size="15"/> <span data-i18n="checkUpdates">Vérifier les mises à jour</span></button>
        <span class="param-note" data-i18n="updateNote">Vous serez informé dès qu'une nouvelle version sera disponible.</span>
      </div>
    </div>
  </div>

  <div class="param-card">
    <div class="param-card-head">
      <div class="lbl"><x-icon name="info" size="16"/> <span data-i18n="about">À propos</span></div>
    </div>
    <div class="param-card-body">
      <div class="about-line"><div class="k" data-i18n="platform">Plateforme</div><div class="v">{{ $nomApp }}</div></div>
      <div class="about-line"><div class="k" data-i18n="version">Version</div><div class="v">v{{ $version }}</div></div>
      <div class="about-line"><div class="k" data-i18n="language">Langue</div><div class="v" id="aboutLang">Français</div></div>
      <div class="about-line"><div class="k" data-i18n="accountType">Type de compte</div><div class="v">{{ $authType === 'societaire' ? 'Sociétaire' : 'Personnel' }}</div></div>
    </div>
  </div>

  <div style="display:flex;gap:10px;margin-top:6px;">
    <button type="button" class="param-btn param-btn-danger" id="btnReset"><x-icon name="refresh" size="15"/> <span data-i18n="reset">Réinitialiser les paramètres</span></button>
  </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    var NS = 'coopec.{{ $cpNs }}.';
    var LEGACY = { theme: 'theme', fontSize: 'coopec.fontSize', densite: 'coopec.densite', lang: 'coopec.lang', notifs: 'coopec.notifs', lastCheck: 'coopec.lastCheck' };
    var KEY = {
        theme: NS + 'theme',
        font: NS + 'fontSize',
        densite: NS + 'densite',
        lang: NS + 'lang',
        notifs: NS + 'notifs',
        lastCheck: NS + 'lastCheck'
    };

    var ZOOM = { petite: 0.9, normale: 1, grande: 1.1, 'tres-grande': 1.25 };
    var LANGS = { fr: 'Français', en: 'English' };
    var LARAVEL_VERSION = '{{ app()->version() }}';

    var I18N = {
        title: { fr: 'Paramètres', en: 'Settings' },
        subtitle: { fr: 'Personnalisez la plateforme selon vos préférences.', en: 'Customize the platform to your preferences.' },
        appearance: { fr: 'Apparence', en: 'Appearance' },
        themeMode: { fr: "Mode d'affichage", en: 'Display mode' },
        themeModeDesc: { fr: 'Choisissez le thème clair, sombre ou automatique.', en: 'Choose light, dark or automatic theme.' },
        auto: { fr: 'Auto', en: 'Auto' },
        autoMini: { fr: "Suivre l'appareil", en: 'Follow device' },
        light: { fr: 'Clair', en: 'Light' },
        dark: { fr: 'Sombre', en: 'Dark' },
        fontSize: { fr: 'Taille de la police', en: 'Font size' },
        fontSizeDesc: { fr: "Agrandissez ou réduisez le texte de toute l'interface.", en: 'Increase or decrease the text of the whole interface.' },
        small: { fr: 'Petite', en: 'Small' },
        normal: { fr: 'Normale', en: 'Normal' },
        large: { fr: 'Grande', en: 'Large' },
        xl: { fr: 'Très grande', en: 'Extra large' },
        screenSize: { fr: "Taille de l'interface / écran", en: 'Interface / screen size' },
        screenSizeDesc: { fr: 'Ajuste la largeur du menu latéral et de la zone de contenu.', en: 'Adjusts the sidebar and content area width.' },
        compact: { fr: 'Compacte', en: 'Compact' },
        compactMini: { fr: 'Menu réduit', en: 'Reduced menu' },
        comfortable: { fr: 'Confortable', en: 'Comfortable' },
        comfortableMini: { fr: 'Recommandé', en: 'Recommended' },
        wide: { fr: 'Large', en: 'Wide' },
        wideMini: { fr: 'Écran étendu', en: 'Extended screen' },
        language: { fr: 'Langue', en: 'Language' },
        languageChoice: { fr: "Langue de l'interface", en: 'Interface language' },
        languageDesc: { fr: 'La préférence est enregistrée sur cet appareil.', en: 'The preference is saved on this device.' },
        notifications: { fr: 'Notifications', en: 'Notifications' },
        notifPrefs: { fr: 'Préférences de notifications', en: 'Notification preferences' },
        notifPrefsDesc: { fr: 'Autoriser les rappels et alertes sur cette plateforme.', en: 'Allow reminders and alerts on this platform.' },
        updates: { fr: 'Mises à jour', en: 'Updates' },
        currentVersion: { fr: 'Version actuelle', en: 'Current version' },
        framework: { fr: 'Framework', en: 'Framework' },
        lastCheck: { fr: 'Dernière vérification', en: 'Last check' },
        never: { fr: 'Jamais', en: 'Never' },
        checkUpdates: { fr: 'Vérifier les mises à jour', en: 'Check for updates' },
        updateNote: { fr: "Vous serez informé dès qu'une nouvelle version sera disponible.", en: 'You will be notified as soon as a new version is available.' },
        about: { fr: 'À propos', en: 'About' },
        platform: { fr: 'Plateforme', en: 'Platform' },
        version: { fr: 'Version', en: 'Version' },
        accountType: { fr: 'Type de compte', en: 'Account type' },
        reset: { fr: 'Réinitialiser les paramètres', en: 'Reset settings' },
        checkOk: { fr: 'Vous êtes à jour (v1.0.0).', en: 'You are up to date (v1.0.0).' },
        checkFail: { fr: 'Impossible de vérifier les mises à jour.', en: 'Unable to check for updates.' }
    };

    function get(key, fallback) {
        var v = localStorage.getItem(key);
        if (v !== null) return v;
        v = localStorage.getItem(LEGACY[key.split('.').pop()] || key);
        return v === null ? fallback : v;
    }
    function set(key, value) {
        localStorage.setItem(key, value);
    }

    function applyAll() {
        var h = document.documentElement;
        var theme = get(KEY.theme, 'auto');
        var dark = theme === 'dark' || ((theme === 'auto') && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (dark) { h.setAttribute('data-theme', 'dark'); } else { h.removeAttribute('data-theme'); }

        var font = get(KEY.font, 'normale');
        h.style.zoom = (ZOOM[font] || 1).toString();

        var densite = get(KEY.densite, 'confortable');
        h.setAttribute('data-densite', densite);

        var lang = get(KEY.lang, 'fr');
        h.setAttribute('lang', lang);
        applyI18n(lang);
        var aboutLang = document.getElementById('aboutLang');
        if (aboutLang) aboutLang.textContent = LANGS[lang] || LANGS.fr;
    }

    function applyI18n(lang) {
        var dict = I18N[lang] ? null : I18N;
        var values = I18N;
        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            var key = el.getAttribute('data-i18n');
            if (values[key] && values[key][lang]) el.textContent = values[key][lang];
        });
    }

    function selectOption(containerId, value) {
        var container = document.getElementById(containerId);
        if (!container) return;
        container.querySelectorAll('.param-option').forEach(function (b) {
            b.classList.toggle('active', b.getAttribute('data-value') === value);
        });
    }

    function bindOptions(containerId, key, transform) {
        var container = document.getElementById(containerId);
        if (!container) return;
        container.addEventListener('click', function (e) {
            var btn = e.target.closest('.param-option');
            if (!btn) return;
            var value = btn.getAttribute('data-value');
            set(key, value);
            selectOption(containerId, value);
            if (transform) transform(value);
        });
    }

    bindOptions('optTheme', KEY.theme, function () { applyAll(); });
    bindOptions('optFont', KEY.font, function () { applyAll(); });
    bindOptions('optDensite', KEY.densite, function () { applyAll(); });
    bindOptions('optLang', KEY.lang, function () {
        applyAll();
        if (window.coopecI18n) window.coopecI18n.apply(document.body);
        if (get(KEY.lang, 'fr') !== 'en') location.reload();
    });

    var notifsToggle = document.getElementById('optNotifs');
    if (notifsToggle) {
        notifsToggle.checked = get(KEY.notifs, 'on') !== 'off';
        notifsToggle.addEventListener('change', function () {
            set(KEY.notifs, notifsToggle.checked ? 'on' : 'off');
        });
    }

    var lastCheckEl = document.getElementById('lastCheckLabel');
    var lastCheck = get(KEY.lastCheck, null);
    if (lastCheck) {
        var d = new Date(Number(lastCheck));
        lastCheckEl.textContent = d.toLocaleString();
        lastCheckEl.removeAttribute('data-i18n');
    }

    var btnUpdate = document.getElementById('btnCheckUpdate');
    if (btnUpdate) {
        btnUpdate.addEventListener('click', function () {
            var original = btnUpdate.innerHTML;
            btnUpdate.innerHTML = '…';
            btnUpdate.disabled = true;
            setTimeout(function () {
                set(KEY.lastCheck, String(Date.now()));
                var d = new Date();
                lastCheckEl.textContent = d.toLocaleString();
                lastCheckEl.removeAttribute('data-i18n');
                btnUpdate.innerHTML = original;
                btnUpdate.disabled = false;
                var lang = get(KEY.lang, 'fr');
                lastCheckEl.textContent = d.toLocaleString() + ' — ' + I18N.checkOk[lang];
            }, 900);
        });
    }

    var btnReset = document.getElementById('btnReset');
    if (btnReset) {
        btnReset.addEventListener('click', function () {
            Object.keys(KEY).forEach(function (k) { localStorage.removeItem(KEY[k]); });
            ['theme', 'coopec.fontSize', 'coopec.densite', 'coopec.lang', 'coopec.notifs', 'coopec.lastCheck'].forEach(function (k) {
                localStorage.removeItem(k);
            });
            location.reload();
        });
    }

    selectOption('optTheme', get(KEY.theme, 'auto'));
    selectOption('optFont', get(KEY.font, 'normale'));
    selectOption('optDensite', get(KEY.densite, 'confortable'));
    selectOption('optLang', get(KEY.lang, 'fr'));
    applyAll();
})();
</script>
@endpush
