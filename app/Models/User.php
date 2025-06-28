<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'photo',
        'password',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
    ];

    // Relations
    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    // Méthodes utilitaires pour la photo
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';

        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }

        return substr($initials, 0, 2); // Maximum 2 initiales
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo && file_exists(public_path('storage/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }

        return null;
    }

    public function hasPhoto()
    {
        return $this->photo && file_exists(public_path('storage/' . $this->photo));
    }

    // Méthodes de permissions
    public function hasPermission($permission)
    {
        // Vérifier d'abord les permissions spécifiques à l'utilisateur
        if (is_array($this->permissions) && in_array($permission, $this->permissions)) {
            return true;
        }

        // Si l'utilisateur n'a pas la permission spécifique, vérifier les permissions du rôle
        return $this->agent && $this->agent->role && $this->agent->role->hasPermission($permission);
    }

    public function hasRole($roleName)
    {
        return $this->agent && $this->agent->role && $this->agent->role->name === $roleName;
    }

    public function hasAnyRole(array $roles)
    {
        return $this->agent && $this->agent->role && in_array($this->agent->role->name, $roles);
    }

    // Vérifier si l'utilisateur peut accéder à une ressource
    public function canAccess($resource, $action = 'view')
    {
        $permission = "{$resource}.{$action}";
        return $this->hasPermission($permission);
    }

    // Vérifier si l'utilisateur est un directeur
    public function isDirecteur()
    {
        return $this->hasAnyRole(['directeur', 'sous-directeur']);
    }

    // Vérifier si l'utilisateur est un chef
    public function isChef()
    {
        return $this->hasAnyRole(['directeur', 'sous-directeur', 'chef-service', 'chef-s-principal']);
    }

    // Obtenir le rôle de l'utilisateur
    public function getRole()
    {
        return $this->agent?->role;
    }

    // Obtenir le nom du rôle
    public function getRoleName()
    {
        return $this->agent?->role?->name;
    }

    // Obtenir le nom d'affichage du rôle
    public function getRoleDisplayName()
    {
        return $this->agent?->role?->display_name ?? 'Aucun rôle';
    }

    // Gestion des permissions spécifiques à l'utilisateur
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
}
