<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'name'];
    private const FILTER_FIELDS = [
        'filterByCategory' => [
            'relation' => 'categories',
            'column' => 'categories.id'
        ],
        'filterBySupplier' => ['column' => 'supplier_id'],
        'filterByPromotion' => ['column' => 'promotion_id'],
        'filterByUnit' => ['column' => 'unit_id'],
        'filterByStatus' => ['column' => 'status'],
    ];

    public function index(Request $request) {
        $query = Product::with('images')->with('categories')->with('unit')->with('supplier')->with('promotion');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function trashed(Request $request) {
        $query = Product::onlyTrashed()->with('images')->with('categories')->with('unit')->with('supplier')->with('promotion');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function show(string $id) {
        $product = Product::with('images')->with('categories')->findOrFail($id);
        return response()->json($product);
    }

    public function showBySlug(string $slug) {
        $product = Product::with('images')->where('slug', $slug)->firstOrFail();
        return response()->json($product);
    }

    public function store(Request $request) {
        $product = Product::create($request->all());
        if ($request->has('addedIds')) {
            $this->addIds($product, json_decode($request->input('addedIds'), true), 'categories');
        }
        if ($request->hasFile('images')) {
            $this->storeImages($request->file('images'), $product);
        }
        if ($request->has('selectedThumbnail')) {
            $this->setThumbnail($product, $request->input('selectedThumbnail'));
        }
        return response()->json(['message' => 'Product created']);
    }

    public function update(Request $request, string $id) {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        if ($request->has('addedIds')) {
            $this->addIds($product, json_decode($request->input('addedIds'), true), 'categories');
        }
        if ($request->has('deletedIds')) {
            $this->removeIds($product, json_decode($request->input('deletedIds'), true), 'categories');
        }
        if ($request->has('deletedImageIds')) {
            $this->deleteImages(json_decode($request->input('deletedImageIds'), true), $product);
        }
        if ($request->hasFile('images')) {
            $this->storeImages($request->file('images'), $product);
        }
        if ($request->has('selectedThumbnail')) {
            $this->setThumbnail($product, $request->input('selectedThumbnail'));
        }
        return response()->json(['message' => 'Product updated']);
    }

    public function destroy(string $id) {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function restore(string $id) {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return response()->json(['message' => 'Product restored']);
    }

    public function forceDelete(string $id) {
        $product = Product::onlyTrashed()->findOrFail($id);
        $this->deleteFolder($product);
        $product->forceDelete();
        return response()->json(['message' => 'Product permanently deleted']);
    }

    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');
        switch ($action) {
            case 'delete':
                Product::destroy($ids);
                return response()->json(['message' => 'Products deleted']);
            case 'restore':
                Product::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Products restored']);
            case 'forceDelete':
                Product::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Products permanently deleted']);
            case 'setStatus':
                Product::whereIn('id', $ids)->update(['status' => $targetId]);
                return response()->json(['message' => 'Status updated successfully']);
            case 'addCategory':
                $products = Product::whereIn('id', $ids)->get();
                foreach ($products as $product) {
                    $product->categories()->syncWithoutDetaching([$targetId]);
                }
                return response()->json(['message' => 'Categories added successfully']);
            case 'removeCategory':
                $products = Product::whereIn('id', $ids)->get();
                foreach ($products as $product) {
                    $product->categories()->detach($targetId);
                }
                return response()->json(['message' => 'Categories removed successfully']);
            case 'setSupplier':
                Product::whereIn('id', $ids)->update(['supplier_id' => $targetId]);
                return response()->json(['message' => 'Supplier updated successfully']);
            case 'setPromotion':
                Product::whereIn('id', $ids)->update(['promotion_id' => $targetId]);
                return response()->json(['message' => 'Promotion updated successfully']);
            case 'removePromotion':
                Product::whereIn('id', $ids)->update(['promotion_id' => null]);
                return response()->json(['message' => 'Promotion removed successfully']);
            case 'setUnit':
                Product::whereIn('id', $ids)->update(['unit_id' => $targetId]);
                return response()->json(['message' => 'Unit updated successfully']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
