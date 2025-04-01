<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'name'];
    private const FILTER_FIELDS = [
        'filterByStatus' => ['column' => 'status'],
    ];
    protected const STATUS_INACTIVE = 0;
    protected const STATUS_ACTIVE = 1;

    public function index(Request $request) {
        $query = Promotion::query();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }
    public function trashed(Request $request) {
        $query = Promotion::onlyTrashed();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }
    public function show(string $id) {
        $promotion = Promotion::findOrFail($id);
        return response()->json($promotion);
    }
    public function store(Request $request) {
        Promotion::create($request->all());
        return response()->json(['message' => 'Promotion created']);
    }
    public function update(Request $request, string $id) {
        $promotion = Promotion::findOrFail($id);
        $promotion->update($request->all());
        return response()->json(['message' => 'Promotion updated']);
    }
    public function destroy(string $id) {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return response()->json(['message' => 'Promotion deleted']);
    }
    public function restore(string $id) {
        $promotion = Promotion::onlyTrashed()->findOrFail($id);
        $promotion->restore();
        return response()->json(['message' => 'Promotion restored']);
    }
    public function forceDelete(string $id) {
        $promotion = Promotion::onlyTrashed()->findOrFail($id);
        $promotion->forceDelete();
        return response()->json(['message' => 'Promotion permanently deleted']);
    }
    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');
        switch ($action) {
            case 'delete':
                Promotion::destroy($ids);
                return response()->json(['message' => 'Promotions deleted']);
            case 'restore':
                Promotion::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Promotions restored']);
            case 'forceDelete':
                Promotion::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Promotions permanently deleted']);
            case 'setStatus':
                Promotion::whereIn('id', $ids)->update(['status' => $targetId]);
                return response()->json(['message' => 'Status updated successfully']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
