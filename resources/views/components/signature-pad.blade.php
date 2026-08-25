@props(['label' => 'Votre signature', 'name' => 'signature', 'id' => null])

@php $padId = $id ?? 'signaturePad_' . \Illuminate\Support\Str::random(8); @endphp

<style>
.sig-wrap{margin:18px 0 4px;}
.sig-label{display:block;font-size:12px;font-weight:700;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.06em;}
.sig-box{position:relative;border:1.5px dashed var(--line);border-radius:12px;background:#fafcff;overflow:hidden;transition:border-color .2s, box-shadow .2s;touch-action:none;}
.sig-box.sig-active{border-color:var(--navy);box-shadow:0 0 0 4px rgba(1,31,98,.08);}
.sig-box canvas{display:block;width:100%;height:120px;cursor:crosshair;background:#fafcff;}
.sig-placeholder{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;gap:8px;color:#b0b7cc;font-size:13px;pointer-events:none;transition:opacity .2s;}
.sig-placeholder svg{opacity:.7;}
.sig-tools{display:flex;align-items:center;justify-content:space-between;margin-top:8px;gap:10px;}
.sig-hint{font-size:11.5px;color:var(--muted);}
.sig-clear{border:none;background:var(--red-bg);color:var(--red);font-weight:700;font-size:12px;padding:7px 14px;border-radius:8px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all .2s;}
.sig-clear:hover{transform:translateY(-1px);}
.sig-error{display:none;color:var(--red);font-size:12px;font-weight:600;margin-top:6px;}
</style>

<div class="sig-wrap">
    <label class="sig-label">{{ $label }}</label>
    <div class="sig-box" id="sigBox_{{ $padId }}">
        <canvas id="sigCanvas_{{ $padId }}" height="120"></canvas>
        <div class="sig-placeholder" id="sigPlaceholder_{{ $padId }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            Signez ici (souris ou doigt)
        </div>
    </div>
    <input type="hidden" name="{{ $name }}" id="sigInput_{{ $padId }}" value="{{ old($name) ?? '' }}">
    <div class="sig-tools">
        <span class="sig-hint">Votre signature atteste de l'opération demandée.</span>
        <button type="button" class="sig-clear" onclick="clearSignature('{{ $padId }}')">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            Effacer
        </button>
    </div>
    <div class="sig-error" id="sigError_{{ $padId }}">Veuillez apposer votre signature avant de confirmer.</div>
</div>

<script>
(function() {
    const padId = '{{ $padId }}';
    const canvas = document.getElementById('sigCanvas_' + padId);
    const box = document.getElementById('sigBox_' + padId);
    const input = document.getElementById('sigInput_' + padId);
    const placeholder = document.getElementById('sigPlaceholder_' + padId);
    const error = document.getElementById('sigError_' + padId);
    const ctx = canvas.getContext('2d');

    let drawing = false;
    let filled = !!input.value;

    function resize() {
        const rect = canvas.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;
        canvas.width = rect.width * dpr;
        canvas.height = 120 * dpr;
        ctx.scale(dpr, dpr);
        ctx.lineWidth = 2.2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#011f62';
        if (filled && input.value) {
            const img = new Image();
            img.onload = function() { ctx.drawImage(img, 0, 0, rect.width, 120); };
            img.src = input.value;
        }
    }

    function pos(e) {
        const rect = canvas.getBoundingClientRect();
        const pt = e.touches ? e.touches[0] : e;
        return { x: pt.clientX - rect.left, y: pt.clientY - rect.top };
    }

    function start(e) {
        e.preventDefault();
        drawing = true;
        const p = pos(e);
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        box.classList.add('sig-active');
        if (placeholder) placeholder.style.opacity = '0';
    }

    function move(e) {
        if (!drawing) return;
        e.preventDefault();
        const p = pos(e);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
    }

    function stop(e) {
        if (!drawing) return;
        e.preventDefault();
        drawing = false;
        input.value = canvas.toDataURL('image/png');
        filled = true;
        box.classList.remove('sig-active');
        if (error) error.style.display = 'none';
    }

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    canvas.addEventListener('mouseup', stop);
    canvas.addEventListener('mouseleave', stop);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', move, { passive: false });
    canvas.addEventListener('touchend', stop, { passive: false });

    const form = canvas.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!input.value || input.value === 'data:image/png;base64,') {
                e.preventDefault();
                if (error) error.style.display = 'block';
                box.classList.add('sig-active');
                box.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    window.addEventListener('resize', resize);
    window.__sigPads = window.__sigPads || [];
    window.__sigPads.push(resize);
    resize();
    if (input.value && !filled) {
        const rect = canvas.getBoundingClientRect();
        const img = new Image();
        img.onload = function() { ctx.drawImage(img, 0, 0, rect.width, 120); };
        img.src = input.value;
    }
})();

function reinitSignaturePads() {
    (window.__sigPads || []).forEach(function(fn) { fn(); });
}
window.reinitSignaturePads = reinitSignaturePads;

function clearSignature(padId) {
    const canvas = document.getElementById('sigCanvas_' + padId);
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById('sigInput_' + padId).value = '';
    const placeholder = document.getElementById('sigPlaceholder_' + padId);
    if (placeholder) placeholder.style.opacity = '1';
    const error = document.getElementById('sigError_' + padId);
    if (error) error.style.display = 'none';
}
</script>
