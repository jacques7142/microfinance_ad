@extends('layouts.app')
@section('title', 'Modifier le rapport')
@section('content')
<div class="card card-pad" style="max-width:480px;">
  <h3 style="font-size:15px;margin-bottom:16px;">Modifier le rapport</h3>
  <form method="POST" action="{{ route('rapports.update', $rapport) }}">
    @csrf @method('PUT')
    <div class="field"><label>Type de rapport</label><input name="type_rapport" value="{{ old('type_rapport', $rapport->type_rapport) }}" required></div>
    <div class="field"><label>Période</label><input name="periode" value="{{ old('periode', $rapport->periode) }}" required></div>
    <div class="field"><label>Format</label>
      <select name="format_export">
        <option value="pdf" {{ $rapport->format_export === 'pdf' ? 'selected' : '' }}>PDF</option>
        <option value="excel" {{ $rapport->format_export === 'excel' ? 'selected' : '' }}>Excel</option>
        <option value="csv" {{ $rapport->format_export === 'csv' ? 'selected' : '' }}>CSV</option>
      </select>
    </div>
    <div style="display:flex;gap:10px;margin-top:18px;">
      <button class="btn btn-navy" type="submit"><x-icon name="check" size="16"/> Enregistrer</button>
      <a href="{{ route('rapports.show', $rapport) }}" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
@endsection