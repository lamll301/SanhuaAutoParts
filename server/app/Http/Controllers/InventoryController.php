<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'batch_number'];
    private const FILTER_FIELDS = [
        'filterByProduct' => ['column' => 'product_id'],
        'filterByImport' => ['column' => 'import_id'],
    ];

    public function index(Request $request) {
        $query = Inventory::with('import')->with('product.unit');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function trashed(Request $request) {
        $query = Inventory::onlyTrashed()->with('import')->with('product');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function show(string $id) {
        $inventory = Inventory::findOrFail($id);
        return response()->json($inventory);
    }

    public function store(Request $request) {
        Inventory::create($request->all());
        return response()->json(['message' => 'Inventory created']);
    }

    public function update(Request $request, string $id) {
        $inventory = Inventory::findOrFail($id);
        $inventory->update($request->all());
        return response()->json(['message' => 'Inventory updated']);
    }

    public function destroy(string $id) {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();
        return response()->json(['message' => 'Inventory deleted']);
    }

    public function restore(string $id) {
        $inventory = Inventory::onlyTrashed()->findOrFail($id);
        $inventory->restore();
        return response()->json(['message' => 'Inventory restored']);
    }

    public function forceDelete(string $id) {
        $inventory = Inventory::onlyTrashed()->findOrFail($id);
        $inventory->forceDelete();
        return response()->json(['message' => 'Inventory permanently deleted']);
    }

    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');
        switch ($action) {
            case 'delete':
                Inventory::destroy($ids);
                return response()->json(['message' => 'Inventories deleted']);
            case 'restore':
                Inventory::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Inventories restored']);
            case 'forceDelete':
                Inventory::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Inventories permanently deleted']);
            case 'setProduct':
                Inventory::whereIn('id', $ids)->update(['product_id' => $targetId]);
                return response()->json(['message' => 'Product updated successfully']);
            case 'setImport':
                Inventory::whereIn('id', $ids)->update(['import_id' => $targetId]);
                return response()->json(['message' => 'Import updated successfully']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
