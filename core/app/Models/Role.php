<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Permission;
class Role extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="role";

    public function permission()
    {
        return $this->belongsTo(Permission::class,'id')->withDefault();
    }

    public static function getPermissionNameById($permissionId)
    {
        // Find the role by ID
        $permission = Permission::find($permissionId);
        if ($permission) {
            return $permission->module."-".$permission->name;
        }

        // Return null if role not found or permission not associated
        return null;
    }
}
