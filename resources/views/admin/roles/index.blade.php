@extends('layouts.app')
@section('title', 'Rôles & Permissions')
@section('content')

<style>
    .roles-layout {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 0;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    .roles-tabs {
        background: var(--bg);
        border-right: 1px solid var(--line);
        padding: 8px;
    }
    .role-tab {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
        transition: all 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        color: var(--ink);
    }
    .role-tab:hover {
        background: rgba(1,31,98,.06);
    }
    .role-tab.active {
        background: var(--navy);
        color: #fff;
    }
    .role-tab .role-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex: none;
    }
    .permissions-panel {
        padding: 24px;
        overflow-y: auto;
        max-height: 600px;
    }
    .perm-group {
        margin-bottom: 24px;
    }
    .perm-group:last-child {
        margin-bottom: 0;
    }
    .perm-group-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--muted);
        letter-spacing: .08em;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--line);
    }
    .perm-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        transition: background 0.15s;
        margin-bottom: 2px;
    }
    .perm-item:hover {
        background: var(--bg);
    }
    .perm-item label {
        font-size: 13px;
        cursor: pointer;
        flex: 1;
    }
    .perm-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid var(--line);
        accent-color: var(--navy);
        cursor: pointer;
        flex: none;
    }
    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--line);
    }
    .panel-header h3 {
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .select-all {
        font-size: 12px;
        color: var(--navy-2);
        font-weight: 700;
        cursor: pointer;
        background: none;
        border: none;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .select-all:hover {
        background: var(--bg);
    }
    .empty-state {
        padding: 40px;
        text-align: center;
        color: var(--muted);
    }
    .empty-state svg {
        margin-bottom: 12px;
        opacity: 0.4;
    }
    .empty-state p {
        font-size: 13px;
    }
    @media(max-width: 768px) {
        .roles-layout {
            grid-template-columns: 1fr;
        }
        .roles-tabs {
            display: flex;
            overflow-x: auto;
            border-right: none;
            border-bottom: 1px solid var(--line);
            padding: 6px;
        }
        .role-tab {
            white-space: nowrap;
            width: auto;
            padding: 10px 14px;
        }
    }
</style>

<form method="POST" action="{{ route('admin.roles.update') }}" id="permissionsForm">
    @csrf
    @method('PUT')

    <div class="roles-layout">
        <div class="roles-tabs">
            @foreach($roles as $index => $role)
                <button type="button" class="role-tab {{ $loop->first ? 'active' : '' }}"
                        onclick="switchRole(this, 'role-{{ $role }}')">
                    <span class="role-dot" style="background: {{ match($role) {
                        'administrateur' => '#c4453b',
                        'gerant' => '#e8a33d',
                        'agent_credit' => '#1e8a5f',
                        'agent_promotion' => '#0a3a8f',
                        'caissier' => '#8a5a12',
                        'comptable' => '#5c6479',
                        default => '#011f62'
                    } }};"></span>
                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                    <span style="margin-left: auto; font-size: 11px; opacity: 0.7;">{{ count($rolePermissions[$role] ?? []) }}</span>
                </button>
            @endforeach
        </div>

        <div class="permissions-panel">
            @foreach($roles as $role)
                <div id="role-{{ $role }}" class="role-panel" style="{{ $loop->first ? '' : 'display: none;' }}">
                    <div class="panel-header">
                        <h3>
                            <x-icon name="shield" size="20"/>
                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                        </h3>
                        <button type="button" class="select-all" onclick="toggleAll('{{ $role }}', this)">
                            Tout sélectionner
                        </button>
                    </div>

                    @forelse($groupes as $groupe => $perms)
                        <div class="perm-group">
                            <div class="perm-group-title">{{ $groupe }}</div>
                            @foreach($perms as $perm)
                                <div class="perm-item">
                                    <input type="checkbox"
                                           name="permissions[{{ $role }}][]"
                                           value="{{ $perm->id }}"
                                           id="perm-{{ $role }}-{{ $perm->id }}"
                                           {{ in_array($perm->slug, $rolePermissions[$role] ?? []) ? 'checked' : '' }}>
                                    <label for="perm-{{ $role }}-{{ $perm->id }}">{{ $perm->nom }}</label>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="empty-state">
                            <x-icon name="lock" size="40"/>
                            <p>Aucune permission configurée.</p>
                        </div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>

    <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
        <button type="submit" class="btn btn-navy">
            <x-icon name="save" size="16"/> Enregistrer les permissions
        </button>
    </div>
</form>

<script>
function switchRole(btn, panelId) {
    document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.role-panel').forEach(p => p.style.display = 'none');
    document.getElementById(panelId).style.display = 'block';
}

function toggleAll(role, btn) {
    const panel = document.getElementById('role-' + role);
    const checkboxes = panel.querySelectorAll('input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(c => c.checked);
    checkboxes.forEach(c => c.checked = !allChecked);
    btn.textContent = allChecked ? 'Tout sélectionner' : 'Tout désélectionner';
}
</script>

@endsection