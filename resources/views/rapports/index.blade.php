@extends('layouts.app')
@section('title', 'Rapports')
@section('content')
<div class="grid" style="grid-template-columns:1fr 1.4fr;gap:16px;">
  <div class="card card-pad">
    <h3 style="font-size:14px;margin-bottom:14px;">Générer un rapport</h3>
    <form method="POST" action="{{ route('rapports.generer') }}">
      @csrf
      <div class="field"><label>Type de rapport</label><input name="type_rapport" placeholder="Ex: Activité mensuelle" required></div>
      <div class="field"><label>Période</label><input name="periode" placeholder="Ex: Juillet 2026" required></div>
      <div class="field"><label>Format</label>
        <select name="format_export">
          <option value="pdf">PDF</option>
          <option value="excel">Excel</option>
          <option value="csv">CSV</option>
        </select>
      </div>
      @if(in_array(auth()->user()->role, ['administrateur','comptable']))
        <div class="field"><label>Agence (laisser vide = consolidé multi-agences)</label>
          <select name="agence_id">
            <option value="">Toutes agences (consolidé)</option>
            @foreach(\App\Models\Agence::orderBy('nom')->get() as $a)
              <option value="{{ $a->id }}">{{ $a->nom }}</option>
            @endforeach
          </select>
        </div>
      @endif
      <button class="btn btn-navy" type="submit"><x-icon name="download" size="16"/> Générer</button>
    </form>
  </div>

  <div class="card">
    <table>
      <tr><th>Type</th><th>Période</th><th>Format</th><th>Portée</th><th>Date</th><th></th></tr>
      @forelse($rapports as $r)
        <tr>
          <td>{{ $r->type_rapport }}</td>
          <td>{{ $r->periode }}</td>
          <td><span class="badge b-navy">{{ strtoupper($r->format_export) }}</span></td>
          <td>{{ $r->estMultiAgences() ? 'Consolidé' : $r->agence->nom }}</td>
          <td>{{ $r->date_generation->format('d/m/Y H:i') }}</td>
          <td style="white-space:nowrap;">
            <a href="{{ route('rapports.show', $r) }}" class="btn btn-sm btn-ghost" style="padding:4px 9px;"><x-icon name="eye" size="13"/></a>
            <a href="{{ route('rapports.edit', $r) }}" class="btn btn-sm btn-ghost" style="padding:4px 9px;"><x-icon name="edit" size="13"/></a>
            <form method="POST" action="{{ route('rapports.destroy', $r) }}" style="display:inline;" onsubmit="return confirm('Supprimer ce rapport ?');">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" style="padding:4px 9px;" type="submit"><x-icon name="x" size="13"/></button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" style="color:#5c6479;">Aucun rapport généré.</td></tr>
      @endforelse
    </table>
  </div>
</div>
@endsection
