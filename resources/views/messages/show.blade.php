@extends('layouts.app')
@section('title', 'Conversation — ' . $societaire->nomComplet())
@section('content')

<style>
.back-link{display:inline-flex;align-items:center;gap:6px;color:var(--muted);text-decoration:none;font-size:13px;font-weight:600;margin-bottom:14px;}
.back-link:hover{color:var(--navy);}
.chat-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;max-width:820px;}
.chat-head{display:flex;align-items:center;gap:12px;padding:16px 20px;border-bottom:1px solid var(--line);background:linear-gradient(135deg,var(--navy),var(--navy-2));}
.chat-head .avatar{width:44px;height:44px;border-radius:50%;background:#fff;color:var(--navy);display:flex;align-items:center;justify-content:center;font-weight:800;font-family:'Sora';font-size:14px;flex:none;overflow:hidden;border:none;}
.chat-head .name{font-family:'Sora';font-weight:700;color:#fff;font-size:14.5px;}
.chat-head .meta{font-size:11.5px;color:#b9c6ea;margin-top:2px;}
.chat-body{height:440px;overflow-y:auto;padding:22px;display:flex;flex-direction:column;gap:12px;background:var(--bg);}
.msg{max-width:78%;padding:11px 15px;border-radius:14px;font-size:13.5px;line-height:1.55;position:relative;box-shadow:0 1px 2px rgba(16,26,46,.06);}
.msg .msg-meta{display:block;font-size:10.5px;margin-top:5px;opacity:.65;}
.msg.societaire{align-self:flex-start;background:var(--surface);border:1px solid var(--line);border-bottom-left-radius:4px;color:var(--ink);}
.msg.societaire .msg-sender{display:block;font-size:10.5px;font-weight:800;color:var(--navy-2);text-transform:uppercase;letter-spacing:.04em;margin-bottom:3px;}
.msg.agence{align-self:flex-end;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);border-bottom-right-radius:4px;font-weight:600;}
.chat-day{text-align:center;font-size:11px;color:var(--muted);margin:4px 0;}
.chat-empty{text-align:center;padding:48px 20px;color:var(--muted);}
.chat-foot{border-top:1px solid var(--line);padding:14px 16px;display:flex;gap:10px;align-items:flex-end;}
.chat-foot textarea{flex:1;resize:none;border:1.5px solid var(--line);border-radius:12px;padding:12px 14px;font-family:'Manrope',sans-serif;font-size:13.5px;min-height:44px;max-height:120px;color:var(--ink);background:#fafcff;transition:all .2s;}
.chat-foot textarea:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 4px rgba(1,31,98,.08);}
.chat-send{border:none;border-radius:12px;width:48px;height:48px;flex:none;background:linear-gradient(135deg,var(--gold),var(--gold-2));color:var(--navy);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;}
.chat-send:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(232,163,61,.35);}
@media(max-width:640px){.msg{max-width:90%;}.content{padding:20px 16px;}.chat-body{height:380px;}}
</style>

<a href="{{ route('messages.index') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    Toutes les conversations
</a>

@if(session('success'))<div class="alert alert-success" style="background:var(--green-bg);color:var(--green);padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px;max-width:820px;">{{ session('success') }}</div>@endif

<div class="chat-card">
    <div class="chat-head">
        <div class="avatar">{{ strtoupper(substr($societaire->prenom,0,1)) }}{{ strtoupper(substr($societaire->nom,0,1)) }}</div>
        <div>
            <div class="name">{{ $societaire->nomComplet() }}</div>
            <div class="meta">{{ $societaire->numero_societaire }} — {{ $societaire->agence->nom ?? '—' }} — {{ $societaire->telephone }}</div>
        </div>
    </div>

    <div class="chat-body" id="chatBody">
        @if($messages->isEmpty())
            <div class="chat-empty">Aucun message dans cette conversation.</div>
        @else
            @php $lastDay = null; @endphp
            @foreach($messages as $message)
                @if($lastDay !== $message->date_envoi->format('d/m/Y'))
                    <div class="chat-day">{{ $message->date_envoi->format('d/m/Y') }}</div>
                    @php $lastDay = $message->date_envoi->format('d/m/Y'); @endphp
                @endif
                <div class="msg {{ $message->expediteur }}">
                    @if($message->expediteur === 'societaire')<span class="msg-sender">Sociétaire</span>@endif
                    {{ $message->contenu }}
                    <span class="msg-meta">{{ $message->date_envoi->format('H:i') }}</span>
                </div>
            @endforeach
        @endif
    </div>

    <form method="POST" action="{{ route('messages.reply', $societaire) }}" class="chat-foot">
        @csrf
        <textarea name="contenu" id="contenu" required placeholder="Répondre à {{ $societaire->prenom }}…" maxlength="2000"></textarea>
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
