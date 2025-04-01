<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class UnitController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'name'];

    public function index(Request $request) {
        $query = Unit::query();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS);
    }
    public function trashed(Request $request) {
        $query = Unit::onlyTrashed();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS);
    }
    public function show(string $id) {
        $unit = Unit::findOrFail($id);
        return response()->json($unit);
    }
    public function store(Request $request) {
        Unit::create($request->all());
        return response()->json(['message' => 'Unit created']);
    }
    public function update(Request $request, string $id) {
        $unit = Unit::findOrFail($id);
        $unit->update($request->all());
        return response()->json(['message' => 'Unit updated']);
    }
    public function destroy(string $id) {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json(['message' => 'Unit deleted']);
    }
    public function restore(string $id) {
        $unit = Unit::onlyTrashed()->findOrFail($id);
        $unit->restore();
        return response()->json(['message' => 'Unit restored']);
    }
    public function forceDelete(string $id) {
        $unit = Unit::onlyTrashed()->findOrFail($id);
        $unit->forceDelete();
        return response()->json(['message' => 'Unit permanently deleted']);
    }
    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        switch ($action) {
            case 'delete':
                Unit::destroy($ids);
                return response()->json(['message' => 'Units deleted']);
            case 'restore':
                Unit::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Units restored']);
            case 'forceDelete':
                Unit::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Units permanently deleted']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
