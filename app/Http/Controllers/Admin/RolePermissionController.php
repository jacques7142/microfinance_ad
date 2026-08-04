<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalActivite;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $roles = Permission::roleList();
        $permissions = Permission::orderBy('groupe')->orderBy('nom')->get();
        $groupes = $permissions->groupBy('groupe');

        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role] = Permission::permissionsParRole($role);
        }

        return view('admin.roles.index', [
            'roles' => $roles,
            'groupes' => $groupes,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['array'],
            'permissions.*.*' => ['exists:permissions,id'],
        ]);

        DB::table('role_permission')->truncate();

        $now = now();
        $inserts = [];
        foreach ($data['permissions'] as $role => $permIds) {
            foreach ($permIds as $permId) {
                $inserts[] = [
                    'role' => $role,
                    'permission_id' => (int) $permId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('role_permission')->insert($inserts);

        JournalActivite::enregistrer('permissions_mises_a_jour', 'Mise à jour des permissions par rôle');

        return redirect()->route('admin.roles.index')->with('success', 'Permissions mises à jour avec succès.');
    }
}