@extends('layouts.societaire')
@section('title', 'Demande de crédit')
@section('content')

<style>
.content-wrapper{max-width:980px;width:100%;margin:0 auto;}
.page-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);padding:26px;}
.page-card h1{margin:0 0 14px;font-family:'Sora';font-size:24px;}
.page-card p{margin:0 0 22px;color:var(--muted);line-height:1.8;}
.field{margin-bottom:16px;}
.field label{display:block;font-size:13px;font-weight:700;color:#5c6479;margin-bottom:8px;}
.field input,.field select{width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--line);font-size:14px;background:#f9fbff;font-family:inherit;}
.btn-submit{border:none;border-radius:14px;padding:13px 18px;font-weight:700;font-size:14px;cursor:pointer;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);width:100%;}
.grid{display:grid;gap:16px;} .g2{grid-template-columns:repeat(2,minmax(0,1fr));}
@media(max-width:760px){.g2{grid-template-columns:1fr;}}
</style>

<div class="content-wrapper">
    <div class="page-card">
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
            <x-signature-pad label="Signature du sociétaire" name="signature" />
            <button class="btn-submit" type="submit">Soumettre ma demande</button>
        </form>
    </div>
</div>

<script>
    function toggleTypeFields() {
        const type = document.getElementById('type').value;
        document.getElementById('sousTypeBlock').style.display = type === '{{ App\Models\Credit::TYPE_ORDINAIRE }}' ? 'block' : 'none';
        document.getElementById('partenaireBlock').style.display = type === '{{ App\Models\Credit::TYPE_PARTENARIAT }}' ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', toggleTypeFields);
</script>
@endsection