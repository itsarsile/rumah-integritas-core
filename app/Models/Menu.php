<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role;
use App\Models\User;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'route',
        'parent_id',
        'order',
        'is_active',
    ];

    /**
     * Roles that can access this menu.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_roles')->withTimestamps();
    }

    /**
     * Users with direct access to this menu.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'menu_user')->withTimestamps();
    }
}
