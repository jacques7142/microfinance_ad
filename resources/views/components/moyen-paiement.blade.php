@props([
    'name' => 'operateur',
    'id' => 'op',
    'value' => 'yas',
    'required' => false,
])

@php
$options = [
    'yas' => [
        'brand' => 'Mixx by Yas',
        'code' => 'YAS',
        'desc' => 'Mobile money',
        'tone' => 'yas',
    ],
    'flooz' => [
        'brand' => 'Flooz',
        'code' => 'FLOOZ',
        'desc' => 'Mobile money',
        'tone' => 'flooz',
    ],
];
@endphp

<div class="pay-methods" role="radiogroup" aria-label="Moyen de paiement">
    @foreach($options as $code => $opt)
        <div class="pay-method">
            <input type="radio" name="{{ $name }}" id="{{ $id }}-{{ $code }}" value="{{ $code }}" {{ $value === $code ? 'checked' : '' }} {{ $required ? 'required' : '' }}>
            <label class="pay-method-card pay-tone-{{ $opt['tone'] }}" for="{{ $id }}-{{ $code }}">
                <span class="pm-logo pm-{{ $opt['tone'] }}">{{ $opt['code'] }}</span>
                <span class="pm-info">
                    <span class="pm-brand">{{ $opt['brand'] }}</span>
                    <span class="pm-desc">{{ $opt['desc'] }}</span>
                </span>
                <span class="pm-radio" aria-hidden="true"></span>
            </label>
        </div>
    @endforeach
</div>

<style>
.pay-methods{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.pay-method{position:relative;}
.pay-method input{position:absolute;opacity:0;pointer-events:none;}
.pay-method-card{display:flex;align-items:center;gap:12px;width:100%;padding:13px 14px;border:2px solid var(--line);border-radius:14px;cursor:pointer;background:var(--surface);transition:all .22s cubic-bezier(.4,0,.2,1);box-sizing:border-box;}
.pay-method-card:hover{border-color:#cdd3e1;transform:translateY(-1px);box-shadow:0 6px 18px rgba(1,31,98,.08);}
.pm-logo{width:40px;height:40px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-family:'Sora';font-weight:800;font-size:11px;letter-spacing:.02em;flex:none;}
.pm-yas{background:linear-gradient(135deg,#FFD200,#F0B400);color:#003070;box-shadow:inset 0 0 0 1px rgba(0,48,112,.15);}
.pm-flooz{background:linear-gradient(135deg,#F06020,#E04E10);color:#fff;}
.pm-info{display:flex;flex-direction:column;gap:2px;min-width:0;}
.pm-brand{font-family:'Sora';font-weight:700;font-size:13px;color:var(--ink);white-space:nowrap;}
.pm-desc{font-size:11px;color:var(--muted);}
.pm-radio{width:20px;height:20px;border-radius:50%;border:2px solid var(--line);flex:none;margin-left:auto;background:var(--surface);display:flex;align-items:center;justify-content:center;transition:all .22s cubic-bezier(.4,0,.2,1);}
.pay-method input:checked + .pay-method-card.pay-tone-yas{border-color:#E0A400;background:linear-gradient(180deg,#FFF9E0,#FFF3BF);box-shadow:0 0 0 4px rgba(255,210,0,.22);}
.pay-method input:checked + .pay-method-card.pay-tone-flooz{border-color:#E05210;background:linear-gradient(180deg,#FFF1EA,#FFE3D4);box-shadow:0 0 0 4px rgba(240,96,32,.2);}
.pay-method input:checked + .pay-method-card .pm-radio{background:#E0A400;border-color:#E0A400;}
.pay-method input:checked + .pay-method-card.pay-tone-flooz .pm-radio{background:#E05210;border-color:#E05210;}
.pay-method input:checked + .pay-method-card .pm-radio::after{content:'✓';font-size:11px;font-weight:800;color:#fff;line-height:1;}
@media(max-width:420px){.pay-methods{grid-template-columns:1fr;}}
</style>