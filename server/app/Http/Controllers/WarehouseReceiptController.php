<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\Export;
use App\Models\Disposal;
use App\Models\Check;
use App\Models\Cancel;
use App\Models\Inventory;
use Illuminate\Http\Request;

class WarehouseReceiptController extends Controller
{
    private const SEARCH_FIELDS = [
        'imports' => ['deliverer'],
        'exports' => [],
        'disposals' => [],
        'checks' => [],
        'cancels' => []
    ];
    private const FILTER_FIELDS = [
        'imports' => [
            'filterByUnapproved' => ['column' => 'approved_by'],
            'filterBySupplier' => ['column' => 'supplier_id']
        ],
        'exports' => [
            'filterByUnapproved' => ['column' => 'approved_by'],
        ],
        'disposals' => [
            'filterByUnapproved' => ['column' => 'approved_by'],
        ],
        'checks' => [
            'filterByUnapproved' => ['column' => 'approved_by'],
        ],
        'cancels' => [
            'filterByUnapproved' => ['column' => 'approved_by'],
        ]
    ];
    public function approveCheck(Request $request, string $id) {
        $approverId = $request->user_id;
        $check = Check::findOrFail($id);
        $check->approved_by = $approverId;
        $check->save();
        $checkDetails = $check->details()->get();
        foreach ($checkDetails as $detail) {
            $inventory = Inventory::findOrFail($detail->inventory_id);
            if ($inventory->quantity !== $detail->quantity) {
                $inventory->quantity = $detail->quantity;
                $inventory->save();
            }
        }
        return response()->json(['message' => 'success'], 200);
    }
    private function decreaseInventory(iterable $details) {
        foreach ($details as $detail) {
            $inventory = Inventory::findOrFail($detail->inventory_id);
            if ($inventory->quantity >= $detail->quantity) {
                $inventory->quantity -= $detail->quantity;
                $inventory->save();
            } else {
                return response()->json(['message' => 'Không đủ số lượng tồn kho cho hàng tồn có ID ' . $detail->inventory_id], 400);
            }
        }
    }
    public function approveExport(Request $request, string $id) {
        $approverId = $request->user_id;
        $export = Export::findOrFail($id);
        $export->approved_by = $approverId;
        $export->save();
        $exportDetails = $export->details()->get();
        $this->decreaseInventory($exportDetails);
        return response()->json(['message' => 'success'], 200);
    }
    public function approveDisposal(Request $request, string $id) {
        $approverId = $request->user_id;
        $disposal = Disposal::findOrFail($id);
        $disposal->approved_by = $approverId;
        $disposal->save();
        $disposalDetails = $disposal->details()->get();
        $this->decreaseInventory($disposalDetails);
        return response()->json(['message' => 'success'], 200);
    }
    public function approveCancel(Request $request, string $id) {
        $approverId = $request->user_id;
        $cancel = Cancel::findOrFail($id);
        $cancel->approved_by = $approverId;
        $cancel->save();
        $cancelDetails = $cancel->details()->get();
        $this->decreaseInventory($cancelDetails);
        return response()->json(['message' => 'success'], 200);
    }
    public function approveImport(Request $request, string $id) {
        $approverId = $request->user_id;
        $import = Import::findOrFail($id);
        $import->approved_by = $approverId;
        $import->save();
        $importDetails = $import->details()->get();
        foreach ($importDetails as $detail) {
            Inventory::create([
                'product_id' => $detail->product_id,
                'quantity' => $detail->quantity,
                'price' => $detail->price,
            ]);
        }
        return response()->json(['message' => 'success'], 200);
    }
    private function getReceiptType() {
        $path = request()->path();
        $segments = explode('/', $path);
        return $segments[1];
    }
    public function index(Request $request) {
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $query = Import::with([
                    'creator:id,name',
                    'supplier:id,name',
                    'approver:id,name',
                ]);
                break;
            case 'exports':
                $query = Export::with([
                    'creator:id,name',
                    'approver:id,name',
                ]);
                break;
            case 'disposals':
                $query = Disposal::with([
                    'creator:id,name',
                    'approver:id,name',
                ]);
                break;
            case 'cancels':
                $query = Cancel::with([
                    'creator:id,name',
                    'approver:id,name',
                ]);
                break;
            case 'checks':
                $query = Check::with([
                    'creator:id,name',
                    'approver:id,name',
                ]);
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS[$type], self::FILTER_FIELDS[$type]);
    }
    public function trashed(Request $request) {
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $query = Import::onlyTrashed()->with([
                    'creator:id,name',
                    'supplier:id,name',
                    'approver:id,name',
                ]);
                break;
            case 'exports':
                $query = Export::onlyTrashed()->with([
                    'creator:id,name',
                    'approver:id,name',
                ]);
                break;
            case 'disposals':
                $query = Disposal::onlyTrashed()->with([
                    'creator:id,name',
                    'approver:id,name',
                ]);;
                break;
            case 'cancels':
                $query = Cancel::onlyTrashed()->with([
                    'creator:id,name',
                    'approver:id,name',
                ]);
                break;
            case 'checks':
                $query = Check::onlyTrashed()->with([
                    'creator:id,name',
                    'approver:id,name',
                ]);
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS[$type], self::FILTER_FIELDS[$type]);
    }
    public function show(string $id) {
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $query = Import::with('details');
                break;
            case 'exports':
                $query = Export::with('details');
                break;
            case 'disposals':
                $query = Disposal::with('details');
                break;
            case 'cancels':
                $query = Cancel::with('details');
                break;
            case 'checks':
                $query = Check::with('details');
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        $record = $query->findOrFail($id);
        return response()->json($record);
    }
    public function store(Request $request) {
        $creatorId = $request->user_id;
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $model = Import::create($request->all() + ['created_by' => $creatorId]);
                break;
            case 'exports':
                $model = Export::create($request->all() + ['created_by' => $creatorId]);
                break;
            case 'disposals':
                $model = Disposal::create($request->all() + ['created_by' => $creatorId]);
                break;
            case 'cancels':
                $model = Cancel::create($request->all() + ['created_by' => $creatorId]);
                break;
            case 'checks':
                $model = Check::create($request->all() + ['created_by' => $creatorId]);
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        if ($request->has('details')) {
            $this->saveDetails($model, $request->details, 'details');
            $totalAmount = $this->calculateTotalAmount($request->details);
            $model->update(['total_amount' => $totalAmount]);
        }
        return response()->json(['message' => 'success'], 201);
    }
    public function update(Request $request, string $id) {
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $model = Import::findOrFail($id);
                break;
            case 'exports':
                $model = Export::findOrFail($id);
                break;
            case 'disposals':
                $model = Disposal::findOrFail($id);
                break;
            case 'cancels':
                $model = Cancel::findOrFail($id);
                break;
            case 'checks':
                $model = Check::findOrFail($id);
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        $model->update($request->all());
        if ($request->has('details')) {
            $this->saveDetails($model, $request->details, 'details');
    
            $totalAmount = $this->calculateTotalAmount($request->details);
            $model->update(['total_amount' => $totalAmount]);
        }
        return response()->json(['message' => 'success'], 200);
    }
    public function destroy(string $id) {
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $model = Import::findOrFail($id);
                break;
            case 'exports':
                $model = Export::findOrFail($id);
                break;
            case 'disposals':
                $model = Disposal::findOrFail($id);
                break;
            case 'cancels':
                $model = Cancel::findOrFail($id);
                break;
            case 'checks':
                $model = Check::findOrFail($id);
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        $model->delete();
        return response()->json(['message' => 'success'], 200);
    }
    public function restore(string $id) {
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $model = Import::onlyTrashed()->findOrFail($id);
                break;
            case 'exports':
                $model = Export::onlyTrashed()->findOrFail($id);
                break;
            case 'disposals':
                $model = Disposal::onlyTrashed()->findOrFail($id);
                break;
            case 'cancels':
                $model = Cancel::onlyTrashed()->findOrFail($id);
                break;
            case 'checks':
                $model = Check::onlyTrashed()->findOrFail($id);
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        $model->restore();
        return response()->json(['message' => 'success'], 200);
    }
    public function forceDelete(string $id) {
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $model = Import::onlyTrashed()->findOrFail($id);
                break;
            case 'exports':
                $model = Export::onlyTrashed()->findOrFail($id);
                break;
            case 'disposals':
                $model = Disposal::onlyTrashed()->findOrFail($id);
                break;
            case 'cancels':
                $model = Cancel::onlyTrashed()->findOrFail($id);
                break;
            case 'checks':
                $model = Check::onlyTrashed()->findOrFail($id);
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        $model->forceDelete();
        return response()->json(['message' => 'success'], 204);
    }
    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');
        $type = $this->getReceiptType();
        switch ($type) {
            case 'imports':
                $model = Import::query();
                break;
            case 'exports':
                $model = Export::query();
                break;
            case 'disposals':
                $model = Disposal::query();
                break;
            case 'cancels':
                $model = Cancel::query();
                break;
            case 'checks':
                $model = Check::query();
                break;
            default:
                return response()->json(['message' => 'Invalid receipt type'], 400);
        }
        switch ($action) {
            case 'delete':
                $model->whereIn('id', $ids)->delete();
                return response()->json(['message' => 'success'], 200);
            case 'restore':
                $model->onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'success'], 200);
            case 'forceDelete':
                $model->onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'success'], 204);
            case 'setSupplier':
                if ($type === 'imports') {
                    $model->whereIn('id', $ids)->update(['supplier_id' => $targetId]);
                    return response()->json(['message' => 'success'], 200);
                }
                break;
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}