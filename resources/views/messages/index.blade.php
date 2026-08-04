@extends('layouts.app')
@section('title', 'Messagerie sociétaires')
@section('content')

<style>
.page-title{font-family:'Sora';font-size:24px;margin:0 0 4px;letter-spacing:-.02em;}
.page-sub{color:var(--muted);margin:0 0 24px;font-size:14px;line-height:1.6;}
.conv-list{display:grid;gap:10px;}
.conv{display:flex;align-items:center;gap:14px;padding:15px 18px;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);text-decoration:none;color:var(--ink);transition:all .2s;}
.conv:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(1,31,98,.14);border-color:var(--navy);}
.conv-avatar{width:44px;height:44px;border-radius:50%;background:var(--navy);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-family:'Sora';font-size:14px;flex:none;}
.conv-body{flex:1;min-width:0;}
.conv-name{font-family:'Sora';font-weight:700;font-size:14px;}
.conv-name .badge{margin-left:8px;}
.conv-last{font-size:12.5px;color:var(--muted);margin-top:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.conv-meta{text-align:right;flex:none;}
.conv-time{font-size:11px;color:var(--muted);}
.conv-unread{display:inline-flex;min-width:20px;height:20px;border-radius:999px;background:var(--gold-2);color:var(--navy);font-size:11px;font-weight:800;align-items:center;justify-content:center;padding:0 6px;margin-top:6px;}
.empty{text-align:center;padding:56px 20px;color:var(--muted);background:var(--surface);border:1px dashed var(--line);border-radius:var(--radius);}
.empty .ico{width:56px;height:56px;border-radius:16px;background:#e8edfb;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:var(--navy);}
.empty p{font-size:14px;margin:0;}
.badge{display:inline-flex;padding:3px 9px;border-radius:100px;font-size:11px;font-weight:700;}
.b-amber{background:var(--amber-bg);color:var(--gold-2);}
</style>

<h1 class="page-title">Messagerie sociétaires</h1>
<p class="page-sub">Répondez aux demandes d'assistance des sociétaires (création de compte, demande de prêt, dépôt, retrait, remboursement…).</p>

@if(session('success'))<div class="alert alert-success" style="background:var(--green-bg);color:var(--green);padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px;">{{ session('success') }}</div>@endif

@if($conversations->isEmpty())
    <div class="empty">
        <div class="ico">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <p>Aucune conversation pour le moment.</p>
    </div>
@else
    <div class="conv-list">
        @foreach($conversations as $conversation)
            @php $lastMsg = $conversation->messages->first(); @endphp
            <a href="{{ route('messages.show', $conversation) }}" class="conv">
                <div class="conv-avatar">{{ strtoupper(substr($conversation->prenom,0,1)) }}{{ strtoupper(substr($conversation->nom,0,1)) }}</div>
                <div class="conv-body">
                    <div class="conv-name">
                        {{ $conversation->nomComplet() }}
                        <span class="badge b-amber">{{ $conversation->agence->nom ?? '—' }}</span>
                    </div>
                    <div class="conv-last">
                        @if($lastMsg)
                            @if($lastMsg->expediteur === 'societaire')Sociétaire : @endif{{ \Illuminate\Support\Str::limit($lastMsg->contenu, 70) }}
                        @else
                            Aucun message
                        @endif
                    </div>
                </div>
                <div class="conv-meta">
                    @if($lastMsg)<div class="conv-time">{{ $lastMsg->date_envoi->format('d/m H:i') }}</div>@endif
                    @if($conversation->non_lus > 0)
                        <div class="conv-unread">{{ $conversation->non_lus }}</div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endif

@endsection
