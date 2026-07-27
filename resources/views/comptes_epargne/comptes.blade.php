<div style="font-size:13px;">
  <p style="font-weight:700;margin-bottom:8px;">{{ $societaire->nomComplet() }}</p>
  @forelse($societaire->comptesEpargne as $c)
    <div data-compte-id="{{ $c->id }}" data-label="{{ $c->type }} — Solde {{ number_format($c->solde,0,',',' ') }} F"
         style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #e6e9f2;">
      <span>{{ $c->type }}</span>
      <span>{{ number_format($c->solde,0,',',' ') }} F</span>
    </div>
  @empty
    <p style="color:#5c6479;">Aucun compte d'épargne pour ce sociétaire.</p>
  @endforelse
</div>
