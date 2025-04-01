<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class VoucherController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'code'];
    private const FILTER_FIELDS = [
        'filterByStatus' => ['column' => 'status'],
    ];
    protected const STATUS_INACTIVE = 0;
    protected const STATUS_ACTIVE = 1;

    public function index(Request $request) {
        $query = Voucher::query();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }
    public function trashed(Request $request) {
        $query = Voucher::onlyTrashed();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }
    public function show(string $id) {
        $voucher = Voucher::findOrFail($id);
        return response()->json($voucher);
    }
    public function store(Request $request) {
        Voucher::create($request->all());
        return response()->json(['message' => 'Voucher created']);
    }
    public function update(Request $request, string $id) {
        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());
        return response()->json(['message' => 'Voucher updated']);
    }
    public function destroy(string $id) {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();
        return response()->json(['message' => 'Voucher deleted']);
    }
    public function restore(string $id) {
        $voucher = Voucher::onlyTrashed()->findOrFail($id);
        $voucher->restore();
        return response()->json(['message' => 'Voucher restored']);
    }
    public function forceDelete(string $id) {
        $voucher = Voucher::onlyTrashed()->findOrFail($id);
        $voucher->forceDelete();
        return response()->json(['message' => 'Voucher permanently deleted']);
    }
    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');
        switch ($action) {
            case 'delete':
                Voucher::destroy($ids);
                return response()->json(['message' => 'Vouchers deleted']);
            case 'restore':
                Voucher::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Vouchers restored']);
            case 'forceDelete':
                Voucher::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Vouchers permanently deleted']);
            case 'setStatus':
                Voucher::whereIn('id', $ids)->update(['status' => $targetId]);
                return response()->json(['message' => 'Status updated successfully']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
