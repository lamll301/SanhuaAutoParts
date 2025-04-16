<?php

namespace App\Http\Controllers;

use App\Models\Export;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'receiver'];
    private const FILTER_FIELDS = [];

    public function index(Request $request) {
        $query = Export::query();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function trashed(Request $request) {
        $query = Export::onlyTrashed();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function show(string $id) {
        $export = Export::findOrFail($id);
        return response()->json($export);
    }

    public function store(Request $request) {
        Export::create($request->all());
        return response()->json(['message' => 'Export created']);
    }

    public function update(Request $request, string $id) {
        $export = Export::findOrFail($id);
        $export->update($request->all());
        return response()->json(['message' => 'Export updated']);
    }

    public function destroy(string $id) {
        $export = Export::findOrFail($id);
        $export->delete();
        return response()->json(['message' => 'Export deleted']);
    }

    public function restore(string $id) {
        $export = Export::onlyTrashed()->findOrFail($id);
        $export->restore();
        return response()->json(['message' => 'Export restored']);
    }

    public function forceDelete(string $id) {
        $export = Export::onlyTrashed()->findOrFail($id);
        $export->forceDelete();
        return response()->json(['message' => 'Export permanently deleted']);
    }

    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        // $targetId = $request->input('targetId');
        switch ($action) {
            case 'delete':
                Export::destroy($ids);
                return response()->json(['message' => 'Inventories deleted']);
            case 'restore':
                Export::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Inventories restored']);
            case 'forceDelete':
                Export::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Inventories permanently deleted']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
