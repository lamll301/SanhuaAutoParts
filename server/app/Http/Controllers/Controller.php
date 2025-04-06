<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Controller
{
    protected function search($query, $key, array $columns = []) {
        if (empty($key)) {
            return $query;
        }
        $query->where(function ($q) use ($key, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$key}%");
            }
        });
        return $query;
    }
    protected function sort($query, $sort) {
        if ($sort['enabled']) {
            $query->orderBy($sort['column'], $sort['type']);
        }
        return $query;
    }
    protected function filter($query, $action, $targetId, array $filters = []) {
        if (!$action || !isset($targetId)) {
            return $query;
        }
        if (isset($filters[$action])) {
            $filter = $filters[$action];
            if (isset($filter['relation'])) {
                $query->whereHas($filter['relation'], function ($q) use ($filter, $targetId) {
                    $q->where($filter['column'], $targetId);
                });
            } else {
                $query->where($filter['column'], $targetId);
            }
        }
        return $query;
    }
    protected function getListResponse($query, Request $request, array $searchableColumns = [], array $filters = []) {
        $query = $this->search($query, $request->query('key'), $searchableColumns);
        $query = $this->filter($query, $request->query('action'), $request->query('targetId'), $filters);
        $query = $this->sort($query, $request->_sort);
        $isPaginated = !$request->boolean('all');
        $items = $isPaginated ? $query->paginate(config('app.per_page')) : $query->get();
        $data = $isPaginated ? $items->items() : $items->toArray();
        $response = [
            'data' => $data,
            '_sort' => $request->_sort,
        ];
        if ($isPaginated) {
            $response['pagination'] = [
                'current_page' => $items->currentPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ];
        }
        return response()->json($response);
    }
    protected function storeImages($files, Model $model) {
        if (empty($files)) return;
        if (!is_array($files)) {
            $files = [$files];
        }

        $storagePath = class_basename($model) . "/{$model->id}";
        
        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }
            
            $path = $file->store($storagePath, 'public');
            $model->images()->create([
                'path' => Storage::url($path),
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }
    protected function deleteImages(array $imageIds, Model $model) {
        if (empty($imageIds)) return;

        $images = $model->images()->whereIn('id', $imageIds)->get();
        foreach ($images as $image) {
            $path = str_replace('/storage', '', $image->path);
            Storage::disk('public')->delete($path);
        }
        $model->images()->whereIn('id', $imageIds)->delete();
    }
    protected function deleteFolder(Model $model) {
        $storagePath = class_basename($model) . "/{$model->id}";
        Storage::disk('public')->deleteDirectory($storagePath);
    }
    protected function setThumbnail(Model $model, $imageName) {
        $model->images()->update(['is_thumbnail' => false]);
        $model->images()->where('filename', $imageName)->update(['is_thumbnail' => true]);
        $model->refresh();
    }
}
