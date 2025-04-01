<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    private const SEARCH_FIELDS = ['id', 'title', 'author'];
    private const FILTER_FIELDS = [
        'filterByStatus' => ['column' => 'status'],
    ];
    protected const STATUS_DRAFT = 0;
    protected const STATUS_PUBLISHED = 1;

    public function index(Request $request) {
        $query = Article::query();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function trashed(Request $request) {
        $query = Article::onlyTrashed();
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function show(string $id) {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    public function store(Request $request) {
        Article::create($request->all());
        return response()->json(['message' => 'Article created']);
    }

    public function update(Request $request, string $id) {
        $article = Article::findOrFail($id);
        $article->update($request->all());
        return response()->json(['message' => 'Article updated']);
    }

    public function destroy(string $id) {
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json(['message' => 'Article deleted']);
    }

    public function restore(string $id) {
        $article = Article::onlyTrashed()->findOrFail($id);
        $article->restore();
        return response()->json(['message' => 'Article restored']);
    }

    public function forceDelete(string $id) {
        $article = Article::onlyTrashed()->findOrFail($id);
        $article->forceDelete();
        return response()->json(['message' => 'Article permanently deleted']);
    }

    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');
        switch ($action) {
            case 'delete':
                Article::destroy($ids);
                return response()->json(['message' => 'Articles deleted']);
            case 'restore':
                Article::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'Articles restored']);
            case 'forceDelete':
                Article::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'Articles permanently deleted']);
            case 'setStatus':
                Article::whereIn('id', $ids)->update(['status' => $targetId]);
                return response()->json(['message' => 'Status updated successfully']);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
