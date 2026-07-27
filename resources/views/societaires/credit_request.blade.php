<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Demande de crédit — COOPEC-AD</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--navy:#011f62;--gold:#e8a33d;--gold-2:#c97f1e;--muted:#5c6479;--bg:#f4f6fb;--surface:#fff;--line:#e6e9f2;--radius:16px;}
*{box-sizing:border-box;}body{margin:0;font-family:'Manrope',sans-serif;background:var(--bg);color:#101a2e;}
.page{min-height:100vh;display:flex;flex-direction:column;}
.header{display:flex;align-items:center;justify-content:space-between;padding:22px 28px;background:#fff;border-bottom:1px solid var(--line);}
.brand{display:flex;align-items:center;gap:12px;}
.mark{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--gold),var(--gold-2));display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:800;color:var(--navy);font-size:16px;}
.brand-text{font-family:'Sora';font-size:16px;font-weight:800;}
.content{max-width:980px;width:100%;margin:0 auto;padding:28px;}
.card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:0 12px 36px rgba(1,31,98,.08);padding:26px;}
.card h1{margin:0 0 14px;font-family:'Sora';font-size:24px;}
.card p{margin:0 0 22px;color:var(--muted);line-height:1.8;}
.field{margin-bottom:16px;}
.field label{display:block;font-size:13px;font-weight:700;color:#5c6479;margin-bottom:8px;}
.field input,.field select{width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--line);font-size:14px;background:#f9fbff;}
.btn{border:none;border-radius:14px;padding:13px 18px;font-weight:700;font-size:14px;cursor:pointer;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);width:100%;}
.alert{padding:14px 16px;border-radius:14px;font-size:13px;margin-bottom:18px;}
.alert-success{background:#e8f6ef;color:#1e8a5f;}
.alert-error{background:#fdecef;color:#c9453b;}
.grid{display:grid;gap:16px;} .g2{grid-template-columns:repeat(2,minmax(0,1fr));}
@media(max-width:760px){.header,.content{padding:18px;} .g2{grid-template-columns:1fr;}}
</style>
</head>
<body>
<div class="page">
  <header class="header">
    <div class="brand"><div class="mark">CA</div><div class="brand-text">COOPEC-AD Togo</div></div>
    <div><a href="{{ route('societaire.dashboard') }}" style="text-decoration:none;color:var(--navy);font-weight:700;">Retour à mon espace</a></div>
  </header>
  <main class="content">
    <div class="card">
      <h1>Demande de crédit</h1>
      <p>Soumettez votre demande de crédit. Le dossier est enregistré avec le statut « Reçue » et sera traité par nos équipes.</p>

      @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
      @if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

      <form method="POST" action="{{ route('societaire.credit.store') }}">
        @csrf
        <div class="field"><label>Type de crédit</label><select name="type" id="type" required onchange="toggleTypeFields()">
            <option value="{{ App\Models\Credit::TYPE_ORDINAIRE }}" {{ old('type') === App\Models\Credit::TYPE_ORDINAIRE ? 'selected' : '' }}>Crédit ordinaire</option>
            <option value="{{ App\Models\Credit::TYPE_PARTENARIAT }}" {{ old('type') === App\Models\Credit::TYPE_PARTENARIAT ? 'selected' : '' }}>Crédit de partenariat</option>
            <option value="{{ App\Models\Credit::TYPE_TONTINE }}" {{ old('type') === App\Models\Credit::TYPE_TONTINE ? 'selected' : '' }}>Crédit tontine adossé</option>
          </select></div>

        <div class="field" id="sousTypeBlock" style="display:none;"><label>Sous-type (crédit ordinaire)</label><select name="sous_type"><option value="">— Sélectionnez —</option>@foreach($sousTypesOrdinaire as $st)<option value="{{ $st }}" {{ old('sous_type') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$st)) }}</option>@endforeach</select></div>

        <div class="field" id="partenaireBlock" style="display:none;"><label>Partenaire</label><input type="text" name="partenaire" value="{{ old('partenaire') }}"></div>

        <div class="grid g2"><div class="field"><label>Montant sollicité (F CFA)</label><input type="number" step="0.01" name="montant" value="{{ old('montant') }}" required></div><div class="field"><label>Durée (mois)</label><input type="number" name="duree_mois" value="{{ old('duree_mois', 12) }}" required></div></div>
        <div class="field"><label>Taux d'intérêt annuel (%)</label><input type="number" step="0.01" name="taux_interet" value="{{ old('taux_interet', 12) }}" required></div>
        <button class="btn" type="submit">Soumettre ma demande</button>
      </form>
    </div>
  </main>
</div>
<script>
  function toggleTypeFields() {
    const type = document.getElementById('type').value;
    document.getElementById('sousTypeBlock').style.display = type === '{{ App\Models\Credit::TYPE_ORDINAIRE }}' ? 'block' : 'none';
    document.getElementById('partenaireBlock').style.display = type === '{{ App\Models\Credit::TYPE_PARTENARIAT }}' ? 'block' : 'none';
  }
  document.addEventListener('DOMContentLoaded', toggleTypeFields);
</script>
</body>
</html>
