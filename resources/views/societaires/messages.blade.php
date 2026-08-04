@extends('layouts.societaire')
@section('title', 'Messagerie')
@section('content')

<style>
.page-title{font-family:'Sora';font-size:24px;margin:0 0 4px;letter-spacing:-.02em;}
.page-sub{color:var(--muted);margin:0 0 24px;font-size:14px;line-height:1.6;max-width:640px;}
.chat-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;max-width:760px;}
.chat-head{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid var(--line);background:linear-gradient(135deg,var(--navy),var(--navy-2));}
.chat-head .staff-avatar{width:42px;height:42px;border-radius:50%;background:#fff;color:var(--navy);display:flex;align-items:center;justify-content:center;font-weight:800;font-family:'Sora';font-size:14px;flex:none;}
.chat-head .staff-name{font-family:'Sora';font-weight:700;color:#fff;font-size:14px;}
.chat-head .staff-role{font-size:11.5px;color:#b9c6ea;margin-top:2px;}
.chat-body{height:420px;overflow-y:auto;padding:22px;display:flex;flex-direction:column;gap:12px;background:var(--bg);}
.msg{max-width:78%;padding:11px 15px;border-radius:14px;font-size:13.5px;line-height:1.55;position:relative;box-shadow:0 1px 2px rgba(16,26,46,.06);}
.msg .msg-meta{display:block;font-size:10.5px;margin-top:5px;opacity:.65;}
.msg.agence{align-self:flex-start;background:var(--surface);border:1px solid var(--line);border-bottom-left-radius:4px;color:var(--ink);}
.msg.agence .msg-sender{display:block;font-size:10.5px;font-weight:800;color:var(--navy-2);text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;}
.msg.societaire{align-self:flex-end;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);border-bottom-right-radius:4px;font-weight:600;}
.chat-day{text-align:center;font-size:11px;color:var(--muted);margin:4px 0;}
.chat-empty{text-align:center;padding:48px 20px;color:var(--muted);}
.chat-empty .ico{width:56px;height:56px;border-radius:16px;background:#e8edfb;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:var(--navy);}
.chat-empty p{font-size:13.5px;margin:0 0 4px;}
.chat-empty span{font-size:12px;color:var(--muted);}
.chat-foot{border-top:1px solid var(--line);padding:14px 16px;display:flex;gap:10px;align-items:flex-end;}
.chat-foot textarea{flex:1;resize:none;border:1.5px solid var(--line);border-radius:12px;padding:12px 14px;font-family:'Manrope',sans-serif;font-size:13.5px;min-height:44px;max-height:120px;color:var(--ink);background:#fafcff;transition:all .2s;}
.chat-foot textarea:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 4px rgba(1,31,98,.08);}
.chat-send{border:none;border-radius:12px;width:48px;height:48px;flex:none;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;}
.chat-send:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(232,163,61,.35);}
.alert{padding:14px 18px;border-radius:12px;font-size:13px;margin-bottom:20px;font-weight:500;display:flex;align-items:center;gap:10px;max-width:760px;}
.alert-success{background:var(--green-bg);color:var(--green);border:1px solid #c5e4d3;}
@media(max-width:640px){.msg{max-width:90%;}.content{padding:20px 16px;}.chat-body{height:360px;}}
</style>

<h1 class="page-title">Messagerie</h1>
<p class="page-sub">Posez vos questions sur la création de compte, une demande de prêt, un dépôt ou tout autre sujet. Un personnel de la COOPEC-AD vous répondra dans les plus brefs délais.</p>

@if(session('success'))<div class="alert alert-success"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>{{ session('success') }}</div>@endif

<div class="chat-card">
    <div class="chat-head">
        <div class="staff-avatar">CA</div>
        <div>
            <div class="staff-name">COOPEC-AD — Service client</div>
            <div class="staff-role">Équipe {{ $societaire->agence->nom ?? 'COOPEC-AD' }}</div>
        </div>
    </div>

    <div class="chat-body" id="chatBody">
        @if($messages->isEmpty())
            <div class="chat-empty">
                <div class="ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <p>Aucun message pour le moment.</p>
                <span>Écrivez à l'équipe de la COOPEC-AD, nous vous répondrons rapidement.</span>
            </div>
        @else
            @php $lastDay = null; @endphp
            @foreach($messages as $message)
                @if($lastDay !== $message->date_envoi->format('d/m/Y'))
                    <div class="chat-day">{{ $message->date_envoi->format('d/m/Y') }}</div>
                    @php $lastDay = $message->date_envoi->format('d/m/Y'); @endphp
                @endif
                <div class="msg {{ $message->expediteur }}">
                    @if($message->expediteur === 'agence')<span class="msg-sender">COOPEC-AD</span>@endif
                    {{ $message->contenu }}
                    <span class="msg-meta">{{ $message->date_envoi->format('H:i') }}</span>
                </div>
            @endforeach
        @endif
    </div>

    <form method="POST" action="{{ route('societaire.messages.send') }}" class="chat-foot">
        @csrf
        <textarea name="contenu" id="contenu" required placeholder="Écrivez votre message…" maxlength="2000"></textarea>
        <button class="chat-send" type="submit" aria-label="Envoyer">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
    </form>
</div>

<script>
function scrollChatToBottom() {
    var body = document.getElementById('chatBody');
    if (body) body.scrollTop = body.scrollHeight;
}
document.addEventListener('DOMContentLoaded', scrollChatToBottom);
</script>

@endsection
