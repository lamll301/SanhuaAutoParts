<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request) {
        $query = Role::query()->with('permissions');
        $key = $request->query('key');
        // tìm kiếm
        if (!empty($key)) {
            if (is_numeric($key)) {
                $query->where('id', $key);
            } else {
                $query->where('name', 'like', "%{$key}%");
            }
        }
        // lọc
        $action = $request->query('action');
        $targetId = $request->query('targetId');
        if ($action && $targetId) {
            switch ($action) {
                case 'filterByPermission':
                    $query->whereHas('permissions', function ($q) use ($targetId) {
                        $q->where('permissions.id', $targetId);
                    });
                    break;
                default:
                    break;
            }
        }
        // sắp xếp
        if ($request->_sort['enabled']) {
            $query->orderBy($request->_sort['column'], $request->_sort['type']);
        }

        $roles = $request->boolean('all')
            ? $query->get()
            : $query->paginate(config('app.admin_max_per_page'));

        return response()->json([
            'data' => $roles instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? $roles->items()
                : $roles->toArray(),
            'pagination' => $roles instanceof \Illuminate\Pagination\LengthAwarePaginator ? [
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
            ] : null,
            '_sort' => $request->_sort,
        ]);
    }
    public function trashed(Request $request) {
        $query = Role::onlyTrashed()->with('permissions');
        $key = $request->query('key');

        if (!empty($key)) {
            if (is_numeric($key)) {
                $query->where('id', $key);
            } else {
                $query->where('name', 'like', "%{$key}%");
            }
        }
        if ($request->boolean('count')) {
            $count = $query->count();
            return response()->json(['count' => $count]);
        }
        if ($request->_sort['enabled']) {
            $query->orderBy($request->_sort['column'], $request->_sort['type']);
        }
        $roles = $request->boolean('all')
            ? $query->get()
            : $query->paginate(config('app.admin_max_per_page'));

        return response()->json([
            'data' => $roles instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? $roles->items()
                : $roles->toArray(),
            'pagination' => $roles instanceof \Illuminate\Pagination\LengthAwarePaginator ? [
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
            ] : null,
            '_sort' => $request->_sort,
        ]);
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|unique:roles',
        ]);

        $role = Role::create($request->all());
        return response()->json($role, 201);
    }
    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => 'required|string|unique:roles',
        ]);

        $role->update($request->all());
        return response()->json($role);
    }
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }
    public function restore(string $id) 
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();
        return response()->json(['message' => 'Role restored']);
    }
    public function forceDelete(string $id) 
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->forceDelete();
        return response()->json(['message' => 'Role permanently deleted']);
    }
    public function handleFormActions(Request $request) 
    {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');

        switch ($action) {
            case 'delete':
                Role::destroy($ids);
                return response()->json(['message' => 'Roles deleted']);
                break;
            case 'restore':
                Role::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Roles restored']);
                break;
            case 'forceDelete':
                Role::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Roles permanently deleted']);
                break;
            case 'addPermission':
                if (!$targetId) {
                    return response()->json(['message' => 'Permission ID is required'], 400);
                }
                foreach ($ids as $id) {
                    $role = Role::find($id);
                    if ($role)
                        $role->permissions()->syncWithoutDetaching([$targetId]);
                }
                return response()->json(['message' => 'Permissions added to roles']);
                break;
            case 'removePermission':
                if (!$targetId) {
                    return response()->json(['message' => 'Permission ID is required'], 400);
                }
                foreach ($ids as $id) {
                    $role = Role::find($id);
                    if ($role)
                        $role->permissions()->detach($targetId);
                }
                return response()->json(['message' => 'Permissions removed from roles']);
                break;
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
