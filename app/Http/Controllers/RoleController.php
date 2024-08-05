<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $sortBy = $request->get('sort_by', 'id');
        $sortDesc = $request->get('sort_desc', 'false') === 'true';

        $query = Role::query();

        if ($sortDesc) {
            $query->orderByDesc($sortBy);
        } else {
            $query->orderBy($sortBy);
        }

        $roles = $query->paginate($perPage, ['*'], 'page', $page);

        return Inertia::render('ManageRoles', [
            'roles' => $roles->items(),
            'total' => $roles->total(),
            'currentPage' => $roles->currentPage(),
            'lastPage' => $roles->lastPage(),
            'perPage' => $roles->perPage(),
        ]);
    }
}
