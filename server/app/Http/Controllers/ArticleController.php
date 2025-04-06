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
        $query = Article::with('images');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function trashed(Request $request) {
        $query = Article::onlyTrashed()->with('images');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }

    public function show(string $id) {
        $article = Article::with('images')->findOrFail($id);
        return response()->json($article);
    }

    public function store(Request $request) {
        $article = Article::create($request->all());
        if ($request->hasFile('images')) {
            $this->storeImages($request->file('images'), $article);
        }
        if ($request->has('selectedThumbnail')) {
            $this->setThumbnail($article, $request->input('selectedThumbnail'));
        }
        return response()->json(['message' => 'Article created']);
    }

    public function update(Request $request, string $id) {
        $article = Article::findOrFail($id);
        $article->update($request->all());
        if ($request->has('deletedImageIds')) {
            $this->deleteImages(json_decode($request->input('deletedImageIds'), true), $article);
        }
        if ($request->hasFile('images')) {
            $this->storeImages($request->file('images'), $article);
        }
        if ($request->has('selectedThumbnail')) {
            $this->setThumbnail($article, $request->input('selectedThumbnail'));
        }
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
        $this->deleteFolder($article);
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
