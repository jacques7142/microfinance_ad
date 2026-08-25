@extends('layouts.societaire')
@section('title', 'Mes paiements')
@section('content')

<style>
.page-title{font-family:'Sora';font-size:24px;margin:0 0 4px;letter-spacing:-.02em;}
.page-sub{color:var(--muted);margin:0 0 26px;font-size:14px;line-height:1.6;}
.filter-tabs{display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;}
.filter-tab{border:1px solid var(--line);background:var(--surface);border-radius:999px;padding:8px 16px;font-size:12.5px;font-weight:700;cursor:pointer;color:var(--muted);font-family:'Manrope',sans-serif;transition:all .2s;}
.filter-tab.active{background:var(--navy);border-color:var(--navy);color:#fff;}
.filter-tab:hover{transform:translateY(-1px);}
.card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;}
table{width:100%;border-collapse:collapse;font-size:13px;}
th{text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;color:var(--muted);padding:12px 18px;border-bottom:1px solid var(--line);letter-spacing:.06em;}
td{padding:13px 18px;border-bottom:1px solid var(--line);}
tr:last-child td{border-bottom:none;}
tr:hover td{background:var(--bg);}
.badge{display:inline-flex;padding:5px 11px;border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.02em;}
.b-green{background:var(--green-bg);color:var(--green);} .b-amber{background:var(--amber-bg);color:var(--gold-2);} .b-red{background:var(--red-bg);color:var(--red);} .b-navy{background:#e8edfb;color:var(--navy-2);}
.op-badge{display:inline-flex;align-items:center;gap:6px;font-weight:700;}
.dot{width:8px;height:8px;border-radius:50%;}
.d-yas{background:#2747b8;} .d-flooz{background:#9333ea;}
.empty{text-align:center;padding:52px 24px;color:var(--muted);}
.empty p{font-size:14px;margin:0 0 16px;}
.btn{border:none;border-radius:10px;padding:10px 16px;font-weight:700;font-size:12.5px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;font-family:'Manrope',sans-serif;}
.btn-gold{background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);}
.btn-ghost{background:var(--surface);border:1px solid var(--line);color:var(--ink);}
.pagination{display:flex;justify-content:center;gap:6px;margin-top:20px;}
.pagination .page-item{list-style:none;}
.pagination .page-link{border:1px solid var(--line);background:var(--surface);border-radius:8px;padding:8px 13px;font-size:12.5px;font-weight:700;color:var(--ink);text-decoration:none;display:inline-flex;}
.pagination .page-link.active,.pagination .page-link[aria-current="page"]{background:var(--navy);color:#fff;border-color:var(--navy);}
@media(max-width:640px){th,td{padding:10px 12px;} .page-title{font-size:20px;}}
</style>

<h1 class="page-title">Mes paiements mobiles</h1>
<p class="page-sub">Suivez vos dépôts, remboursements et retraits effectués par Mixx by Yas ou Flooz.</p>

<div class="filter-tabs">
    <button class="filter-tab active" data-f="all" onclick="filterTab('all')">Tous</button>
    <button class="filter-tab" data-f="pending" onclick="filterTab('pending')">En attente</button>
    <button class="filter-tab" data-f="completed" onclick="filterTab('completed')">Confirmés</button>
    <button class="filter-tab" data-f="echec" onclick="filterTab('echec')">Échecs</button>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Référence</th>
                <th>Opération</th>
                <th>Moyen</th>
                <th>Montant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paiements as $paiement)
                <tr data-f="{{ $paiement->statut }}">
                    <td>{{ $paiement->date_initiation->format('d/m/Y H:i') }}</td>
                    <td style="font-weight:700;">{{ $paiement->reference_interne }}</td>
                    <td>{{ $paiement->typeLibelle() }}</td>
                    <td><span class="op-badge"><span class="dot {{ $paiement->operateur === 'yas' ? 'd-yas' : 'd-flooz' }}"></span>{{ $paiement->operateurLibelle() }}</span></td>
                    <td><strong>{{ number_format($paiement->montant, 0, ',', ' ') }} F</strong></td>
                    <td>
                        @if($paiement->statut === 'completed')
                            <span class="badge b-green">Confirmé</span>
                        @elseif($paiement->statut === 'pending')
                            <a href="{{ route('societaire.paiement.statut', $paiement) }}" class="badge b-amber" style="text-decoration:none;">En attente</a>
                        @elseif($paiement->statut === 'notcompleted')
                            <span class="badge b-red">Non abouti</span>
                        @else
                            <span class="badge b-navy">Échec</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty">
                            <p>Aucun paiement mobile pour le moment.</p>
                            <a href="{{ route('societaire.depot') }}" class="btn btn-gold">Effectuer un premier dépôt</a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($paiements->hasPages())
    <div class="pagination">{{ $paiements->links() }}</div>
@endif

@push('scripts')
<script>
function filterTab(f) {
    document.querySelectorAll('.filter-tab').forEach(function(btn) {
        btn.classList.toggle('active', btn.dataset.f === f);
    });
    document.querySelectorAll('tbody tr').forEach(function(row) {
        var s = row.dataset.f;
        var show = f === 'all'
            || (f === 'echec' && (s === 'notcompleted' || s === 'failed'))
            || s === f;
        row.style.display = show ? '' : 'none';
    });
}
</script>
@endpush
@endsection