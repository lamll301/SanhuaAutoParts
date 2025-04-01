<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'name'];

    public function index(Request $request) {
        $query = Category::query();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS);
    }
    public function trashed(Request $request) {
        $query = Category::onlyTrashed();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS);
    }
    public function show(string $id) {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }
    public function store(Request $request) {
        Category::create($request->all());
        return response()->json(['message' => 'Category created']);
    }
    public function update(Request $request, string $id) {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json(['message' => 'Category updated']);
    }
    public function destroy(string $id) {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
    public function restore(string $id) {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        return response()->json(['message' => 'Category restored']);
    }
    public function forceDelete(string $id) {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        return response()->json(['message' => 'Category permanently deleted']);
    }
    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        switch ($action) {
            case 'delete':
                Category::destroy($ids);
                return response()->json(['message' => 'Categories deleted']);
            case 'restore':
                Category::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Categories restored']);
            case 'forceDelete':
                Category::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Categories permanently deleted']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
