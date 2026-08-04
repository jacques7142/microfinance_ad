<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['nom' => 'Voir le tableau de bord', 'slug' => 'dashboard.view', 'groupe' => 'Général'],
            ['nom' => 'Gérer les sociétaires', 'slug' => 'societaires.manage', 'groupe' => 'Sociétaires'],
            ['nom' => 'Voir les sociétaires', 'slug' => 'societaires.view', 'groupe' => 'Sociétaires'],
            ['nom' => 'Créer des sociétaires', 'slug' => 'societaires.create', 'groupe' => 'Sociétaires'],
            ['nom' => 'Gérer les crédits', 'slug' => 'credits.manage', 'groupe' => 'Crédits'],
            ['nom' => 'Voir les crédits', 'slug' => 'credits.view', 'groupe' => 'Crédits'],
            ['nom' => 'Créer des crédits', 'slug' => 'credits.create', 'groupe' => 'Crédits'],
            ['nom' => 'Instruire les crédits', 'slug' => 'credits.instruire', 'groupe' => 'Crédits'],
            ['nom' => 'Valider les crédits', 'slug' => 'credits.valider', 'groupe' => 'Crédits'],
            ['nom' => 'Rejeter les crédits', 'slug' => 'credits.rejeter', 'groupe' => 'Crédits'],
            ['nom' => 'Gérer les opérations guichet', 'slug' => 'guichet.manage', 'groupe' => 'Guichet'],
            ['nom' => 'Gérer la tontine', 'slug' => 'tontine.manage', 'groupe' => 'Tontine'],
            ['nom' => 'Valider les collectes', 'slug' => 'tontine.valider', 'groupe' => 'Tontine'],
            ['nom' => 'Gérer les rapports', 'slug' => 'rapports.manage', 'groupe' => 'Reporting'],
            ['nom' => 'Voir les rapports', 'slug' => 'rapports.view', 'groupe' => 'Reporting'],
            ['nom' => 'Gérer les utilisateurs', 'slug' => 'admin.users.manage', 'groupe' => 'Administration'],
            ['nom' => 'Gérer les agences', 'slug' => 'admin.agences.manage', 'groupe' => 'Administration'],
            ['nom' => 'Gérer les rôles & permissions', 'slug' => 'admin.roles.manage', 'groupe' => 'Administration'],
            ['nom' => 'Voir le journal d\'activité', 'slug' => 'admin.journal.view', 'groupe' => 'Administration'],
            ['nom' => 'Gérer les paramètres système', 'slug' => 'admin.settings.manage', 'groupe' => 'Administration'],
        ];

        $now = now();
        foreach ($permissions as &$p) {
            $p['created_at'] = $now;
            $p['updated_at'] = $now;
        }

        DB::table('permissions')->insert($permissions);

        $rolePermissions = [
            'administrateur' => [
                'dashboard.view', 'societaires.manage', 'societaires.view', 'societaires.create',
                'credits.manage', 'credits.view', 'credits.create', 'credits.instruire',
                'credits.valider', 'credits.rejeter',
                'guichet.manage', 'tontine.manage', 'tontine.valider',
                'rapports.manage', 'rapports.view',
                'admin.users.manage', 'admin.agences.manage', 'admin.roles.manage',
                'admin.journal.view', 'admin.settings.manage',
            ],
            'gerant' => [
                'dashboard.view', 'societaires.view', 'societaires.create',
                'credits.view', 'credits.create', 'credits.valider', 'credits.rejeter',
                'rapports.view',
            ],
            'agent_credit' => [
                'dashboard.view', 'societaires.view', 'societaires.create',
                'credits.view', 'credits.create', 'credits.instruire',
            ],
            'agent_promotion' => [
                'dashboard.view', 'tontine.manage',
            ],
            'caissier' => [
                'dashboard.view', 'guichet.manage', 'tontine.valider',
            ],
            'comptable' => [
                'dashboard.view', 'rapports.view', 'rapports.manage',
            ],
        ];

        $inserts = [];
        foreach ($rolePermissions as $role => $slugs) {
            foreach ($slugs as $slug) {
                $permId = DB::table('permissions')->where('slug', $slug)->value('id');
                if ($permId) {
                    $inserts[] = [
                        'role' => $role,
                        'permission_id' => $permId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        DB::table('role_permission')->insert($inserts);
    }

    public function down(): void
    {
        DB::table('permissions')->truncate();
        DB::table('role_permission')->truncate();
    }
};