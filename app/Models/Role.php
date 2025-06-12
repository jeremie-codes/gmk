<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_active',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    // Relations
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Méthodes utilitaires
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function grantPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }
    }

    public function revokePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        $this->permissions = array_values(array_diff($permissions, [$permission]));
        $this->save();
    }

    public function syncPermissions(array $permissions)
    {
        $this->permissions = $permissions;
        $this->save();
    }

    // Permissions disponibles dans le système
    public static function getAvailablePermissions()
    {
        return [
            // Gestion des agents
            'agents.view' => 'Voir les agents',
            'agents.create' => 'Créer des agents',
            'agents.edit' => 'Modifier les agents',
            'agents.delete' => 'Supprimer les agents',
            'agents.export' => 'Exporter les agents',

            // Gestion des présences
            'presences.view' => 'Voir les présences',
            'presences.create' => 'Créer des présences',
            'presences.edit' => 'Modifier les présences',
            'presences.delete' => 'Supprimer les présences',
            'presences.export' => 'Exporter les présences',

            // Gestion des congés
            'conges.view' => 'Voir les congés',
            'conges.create' => 'Créer des demandes de congé',
            'conges.edit' => 'Modifier les demandes de congé',
            'conges.delete' => 'Supprimer les demandes de congé',
            'conges.approve_directeur' => 'Approuver en tant que directeur',
            'conges.validate_drh' => 'Valider en tant que DRH',
            'conges.view_all' => 'Voir tous les congés',
            'conges.view_own' => 'Voir ses propres congés',

            // Cotation des agents
            'cotations.view' => 'Voir les cotations',
            'cotations.create' => 'Créer des cotations',
            'cotations.edit' => 'Modifier les cotations',
            'cotations.delete' => 'Supprimer les cotations',
            'cotations.generate' => 'Générer des cotations automatiques',

            // Gestion des utilisateurs et rôles
            'users.view' => 'Voir les utilisateurs',
            'users.create' => 'Créer des utilisateurs',
            'users.edit' => 'Modifier les utilisateurs',
            'users.delete' => 'Supprimer les utilisateurs',
            'roles.view' => 'Voir les rôles',
            'roles.edit' => 'Modifier les rôles et permissions',

            // Rapports et statistiques
            'reports.view' => 'Voir les rapports',
            'reports.export' => 'Exporter les rapports',
            'dashboard.view' => 'Accéder au tableau de bord',

            // Administration système
            'system.settings' => 'Paramètres système',
            'system.backup' => 'Sauvegarde système',
            'system.logs' => 'Voir les logs système',
        ];
    }

    // Rôles prédéfinis avec leurs permissions
    public static function getDefaultRoles()
    {
        return [
            'agent' => [
                'name' => 'agent',
                'display_name' => 'Agent',
                'description' => 'Agent de base avec accès limité',
                'permissions' => [
                    'dashboard.view',
                    'conges.view_own',
                    'conges.create',
                    'presences.view',
                ],
            ],
            'responsable_service' => [
                'name' => 'responsable_service',
                'display_name' => 'Responsable de Service',
                'description' => 'Responsable d\'un service avec permissions étendues',
                'permissions' => [
                    'dashboard.view',
                    'agents.view',
                    'presences.view',
                    'presences.create',
                    'presences.edit',
                    'conges.view',
                    'conges.create',
                    'conges.edit',
                    'cotations.view',
                    'reports.view',
                ],
            ],
            'sous_directeur' => [
                'name' => 'sous_directeur',
                'display_name' => 'Sous-Directeur',
                'description' => 'Sous-directeur avec permissions de supervision',
                'permissions' => [
                    'dashboard.view',
                    'agents.view',
                    'agents.edit',
                    'presences.view',
                    'presences.create',
                    'presences.edit',
                    'conges.view',
                    'conges.create',
                    'conges.edit',
                    'conges.approve_directeur',
                    'cotations.view',
                    'cotations.create',
                    'reports.view',
                    'reports.export',
                ],
            ],
            'directeur' => [
                'name' => 'directeur',
                'display_name' => 'Directeur',
                'description' => 'Directeur avec pleins pouvoirs sur son département',
                'permissions' => [
                    'dashboard.view',
                    'agents.view',
                    'agents.create',
                    'agents.edit',
                    'agents.delete',
                    'presences.view',
                    'presences.create',
                    'presences.edit',
                    'presences.delete',
                    'conges.view_all',
                    'conges.create',
                    'conges.edit',
                    'conges.delete',
                    'conges.approve_directeur',
                    'cotations.view',
                    'cotations.create',
                    'cotations.edit',
                    'cotations.generate',
                    'reports.view',
                    'reports.export',
                    'users.view',
                ],
            ],
            'rh' => [
                'name' => 'rh',
                'display_name' => 'RH',
                'description' => 'Agent RH avec accès aux fonctions RH',
                'permissions' => [
                    'dashboard.view',
                    'agents.view',
                    'agents.create',
                    'agents.edit',
                    'presences.view',
                    'presences.create',
                    'presences.edit',
                    'conges.view_all',
                    'conges.create',
                    'conges.edit',
                    'cotations.view',
                    'cotations.create',
                    'cotations.edit',
                    'reports.view',
                    'reports.export',
                    'users.view',
                    'users.create',
                    'users.edit',
                ],
            ],
            'drh' => [
                'name' => 'drh',
                'display_name' => 'DRH',
                'description' => 'Directeur des Ressources Humaines - Accès complet',
                'permissions' => [
                    'dashboard.view',
                    'agents.view',
                    'agents.create',
                    'agents.edit',
                    'agents.delete',
                    'agents.export',
                    'presences.view',
                    'presences.create',
                    'presences.edit',
                    'presences.delete',
                    'presences.export',
                    'conges.view_all',
                    'conges.create',
                    'conges.edit',
                    'conges.delete',
                    'conges.approve_directeur',
                    'conges.validate_drh',
                    'cotations.view',
                    'cotations.create',
                    'cotations.edit',
                    'cotations.delete',
                    'cotations.generate',
                    'reports.view',
                    'reports.export',
                    'users.view',
                    'users.create',
                    'users.edit',
                    'users.delete',
                    'roles.view',
                    'roles.edit',
                    'system.settings',
                    'system.backup',
                    'system.logs',
                ],
            ],
        ];
    }

    public function getBadgeClass()
    {
        return match($this->name) {
            'agent' => 'bg-gray-100 text-gray-800',
            'responsable_service' => 'bg-blue-100 text-blue-800',
            'sous_directeur' => 'bg-indigo-100 text-indigo-800',
            'directeur' => 'bg-purple-100 text-purple-800',
            'rh' => 'bg-green-100 text-green-800',
            'drh' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getIcon()
    {
        return match($this->name) {
            'agent' => 'bx-user',
            'responsable_service' => 'bx-user-check',
            'sous_directeur' => 'bx-user-voice',
            'directeur' => 'bx-shield',
            'rh' => 'bx-group',
            'drh' => 'bx-crown',
            default => 'bx-user',
        };
    }
}
