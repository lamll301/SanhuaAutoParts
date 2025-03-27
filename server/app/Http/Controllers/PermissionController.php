<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'name'];

    public function index(Request $request) {
        $query = Permission::query();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS);
    }
    public function trashed(Request $request) {
        $query = Permission::onlyTrashed();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS);
    }
    public function show(string $id) {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }
    public function store(Request $request) {
        Permission::create($request->all());
        return response()->json(['message' => 'Permission created']);
    }
    public function update(Request $request, string $id) {
        $permission = Permission::findOrFail($id);
        $permission->update($request->all());
        return response()->json(['message' => 'Permission updated']);
    }
    public function destroy(string $id) {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(['message' => 'Permission deleted']);
    }
    public function restore(string $id) {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        $permission->restore();
        return response()->json(['message' => 'Permission restored']);
    }
    public function forceDelete(string $id) {
        $permission = Permission::onlyTrashed()->findOrFail($id);
        $permission->forceDelete();
        return response()->json(['message' => 'Permission permanently deleted']);
    }
    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        switch ($action) {
            case 'delete':
                Permission::destroy($ids);
                return response()->json(['message' => 'Permissions deleted']);
            case 'restore':
                Permission::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Permissions restored']);
            case 'forceDelete':
                Permission::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Permissions permanently deleted']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
