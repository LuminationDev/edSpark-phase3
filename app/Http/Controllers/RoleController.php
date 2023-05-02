<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function fetchRoleByCode($roleCode)
    {
        $rc = Str::upper($roleCode);
        $role = Role::where('role_name', '=', $rc)->first();
        if ($role) {
            $result = [
                'id' => $role->id,
                'role_name' => $role->role_name,
                'role_value' => $role->role_value
            ];
            return response()->json($result);
        } else {
            return response()->json('No role found');
        }
    }

    public function fetchAllRoles()
    {
        $roles = Role::all();
        $data = [];

        if ($roles) {
            foreach($roles as $role) {

                if (
                    $role->role_name !== 'Superadmin' ||
                    $role->role_name !== 'Administrator' ||
                    $role->role_name !== 'Editor' ||
                    $role->role_name !== 'Viewer' ||
                    $role->role_name !== 'Moderator'
                ) {
                    $result = [
                        'id' => $role->id,
                        'role_name' => $role->role_name,
                        'role_value' => $role->role_value
                    ];
                    $data[] = $result;
                }

            }
        }

        return response()->json($data);

    }
}
