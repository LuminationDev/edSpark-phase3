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
}
