<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::query();
        $key = $request->query('key');

        if (!empty($key)) {
            if (is_numeric($key)) {
                $query->where('id', $key);
            } else {
                $query->where('name', 'like', "%{$key}%");
            }
        }
        if ($request->_sort['enabled']) {
            $query->orderBy($request->_sort['column'], $request->_sort['type']);
        }

        $permissions = $request->boolean('all')
            ? $query->get()
            : $query->paginate(config('app.admin_max_per_page'));

        return response()->json([
            'data' => $permissions instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? $permissions->items()
                : $permissions->toArray(),
            'pagination' => $permissions instanceof \Illuminate\Pagination\LengthAwarePaginator ? [
                'current_page' => $permissions->currentPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total(),
            ] : null,
            '_sort' => $request->_sort,
        ]);
    }

    public function trashed(Request $request)
    {
        $query = Permission::onlyTrashed();
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
        $permissions = $request->boolean('all')
            ? $query->get()
            : $query->paginate(config('app.admin_max_per_page'));

        return response()->json([
            'data' => $permissions instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? $permissions->items()
                : $permissions->toArray(),
            'pagination' => $permissions instanceof \Illuminate\Pagination\LengthAwarePaginator ? [
                'current_page' => $permissions->currentPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total(),
            ] : null,
            '_sort' => $request->_sort,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:255'
        ]);

        $permission = Permission::create($request->all());
        return response()->json($permission, 201);
    }

    public function show(string $id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }

    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|string|max:50',
            'description' => 'nullable|string|max:255'
        ]);

        $permission->update($request->all());
        return response()->json($permission);
    }

    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(['message' => 'Permission deleted']);
    }

    public function restore(string $id) 
    {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        $permission->restore();
        return response()->json(['message' => 'Permission restored']);
    }

    public function forceDelete(string $id) 
    {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        $permission->forceDelete();
        return response()->json(['message' => 'Permission permanently deleted']);
    }

    public function handleFormActions(Request $request) 
    {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);

        switch ($action) {
            case 'delete':
                Permission::destroy($ids);
                return response()->json(['message' => 'Permissions deleted']);
                break;
            case 'restore':
                Permission::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Permissions restored']);
                break;
            case 'forceDelete':
                Permission::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Permissions permanently deleted']);
                break;
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
