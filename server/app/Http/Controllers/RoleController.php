<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'name'];
    private const FILTER_FIELDS = [
        'filterByPermission' => [
            'relation' => 'permissions',
            'column' => 'permissions.id'
        ],
    ];

    public function index(Request $request) {
        $query = Role::query()->with('permissions');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }
    public function trashed(Request $request) {
        $query = Role::onlyTrashed()->with('permissions');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }
    public function show(string $id) {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }
    public function store(RoleRequest $request) {
        Role::create($request->all());
        return response()->json(['message' => 'Role created']);
    }
    public function update(RoleRequest $request, string $id) {
        $role = Role::findOrFail($id);
        $role->update($request->all());
        return response()->json(['message' => 'Role updated']);
    }
    public function destroy(string $id) {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }
    public function restore(string $id) {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->restore();
        return response()->json(['message' => 'Role restored']);
    }
    public function forceDelete(string $id) {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->forceDelete();
        return response()->json(['message' => 'Role permanently deleted']);
    }
    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');

        switch ($action) {
            case 'delete':
                Role::destroy($ids);
                return response()->json(['message' => 'Roles deleted']);
            case 'restore':
                Role::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Roles restored']);
            case 'forceDelete':
                Role::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Roles permanently deleted']);
            case 'addPermission':
                if (!$targetId) {
                    return response()->json(['message' => 'Permission ID is required'], 400);
                }
                $roles = Role::whereIn('id', $ids)->get();
                foreach ($roles as $role) {
                    $role->permissions()->syncWithoutDetaching([$targetId]);
                }
                return response()->json(['message' => 'Permissions added to roles']);
            case 'removePermission':
                if (!$targetId) {
                    return response()->json(['message' => 'Permission ID is required'], 400);
                }
                $roles = Role::whereIn('id', $ids)->get();
                foreach ($roles as $role) {
                    $role->permissions()->detach($targetId);
                }
                return response()->json(['message' => 'Permissions removed from roles']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
