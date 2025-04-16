<?php

namespace App\Http\Controllers;

use App\Models\Import;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'deliverer'];
    private const FILTER_FIELDS = [
        'filterBySupplier' => ['column' => 'supplier_id'],
    ];

    public function index(Request $request) {
        $query = Import::with('supplier');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function trashed(Request $request) {
        $query = Import::onlyTrashed()->with('supplier');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function show(string $id) {
        $import = Import::with('importDetails')->findOrFail($id);
        return response()->json($import);
    }

    public function store(Request $request) {
        $import = Import::create($request->all());
        if ($request->has('import_details')) {
            $this->saveDetails($import, $request->input('import_details'), 'importDetails');
            
            $totalAmount = $this->calculateTotalAmount($request->input('import_details'));
            $import->update(['total_amount' => $totalAmount]);
        }
        return response()->json(['message' => 'Import created']);
    }

    public function update(Request $request, string $id) {
        $import = Import::findOrFail($id);
        $import->update($request->all());
        if ($request->has('import_details')) {
            $this->saveDetails($import, $request->input('import_details'), 'importDetails');
            
            $totalAmount = $this->calculateTotalAmount($request->input('import_details'));
            $import->update(['total_amount' => $totalAmount]);
        }
        return response()->json(['message' => 'Import updated']);
    }

    public function destroy(string $id) {
        $import = Import::findOrFail($id);
        $import->delete();
        return response()->json(['message' => 'Import deleted']);
    }

    public function restore(string $id) {
        $import = Import::onlyTrashed()->findOrFail($id);
        $import->restore();
        return response()->json(['message' => 'Import restored']);
    }

    public function forceDelete(string $id) {
        $import = Import::onlyTrashed()->findOrFail($id);
        $import->forceDelete();
        return response()->json(['message' => 'Import permanently deleted']);
    }

    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');
        switch ($action) {
            case 'delete':
                Import::destroy($ids);
                return response()->json(['message' => 'Imports deleted']);
            case 'restore':
                Import::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Imports restored']);
            case 'forceDelete':
                Import::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Imports permanently deleted']);
            case 'setSupplier':
                Import::whereIn('id', $ids)->update(['supplier_id' => $targetId]);
                return response()->json(['message' => 'Supplier updated successfully']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
