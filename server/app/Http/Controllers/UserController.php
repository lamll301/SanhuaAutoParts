<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private const SEARCH_FIELDS = ['phone', 'name'];
    private const FILTER_FIELDS = [
        'filterByRole' => ['column' => 'role_id'],
        'filterByStatus' => ['column' => 'status'],
    ];

    public function updateProfile(Request $request) {
        try {
            $user = User::findOrFail(Auth::id());
            $user->update($request->only([
                'name', 'email', 'phone', 'date_of_birth', 'city_id', 'district_id', 'ward_id',  'address', 'avatar_id'
            ]));
            if ($request->hasFile('avatar')) {
                if ($user->avatar_id) {
                    $oldImage = $user->avatar;
                    if ($oldImage) {
                        $path = str_replace('/storage', '', $oldImage->path);
                        Storage::disk('public')->delete($path);
                        $oldImage->delete();
                    }
                }
                $storagePath = "User/{$user->id}";
                $file = $request->file('avatar');
                $path = $file->store($storagePath, 'public');
                $image = $user->avatar()->create([
                    'path' => Storage::url($path),
                    'filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
                $user->avatar_id = $image->id;
                $user->save();
            }
            return response()->json($user->load(['avatar:id,path']));
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        }
    }

    public function updatePassword(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|min:6',
                'new_password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::findOrFail(Auth::id());

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['error' => 'Password is incorrect'], 422);
            }

            $user->update([
                'password' => $request->new_password
            ]);

            return response()->json(['message' => 'success']);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        }
    }

    public function index(Request $request) {
        $query = User::query()->with('role');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }
    public function trashed(Request $request) {
        $query = User::onlyTrashed()->with('role');
        return $this->getListResponse($query, $request, self::SEARCH_FIELDS, self::FILTER_FIELDS);
    }
    public function show(string $id) {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    public function store(UserRequest $request) {
        User::create($request->all());
        return response()->json(['message' => 'success'], 201);
    }
    public function update(UserRequest $request, string $id) {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json(['message' => 'success'], 200);
    }
    public function destroy(string $id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'success'], 200);
    }
    public function restore(string $id) {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return response()->json(['message' => 'success'], 200);
    }
    public function forceDelete(string $id) {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return response()->json(['message' => 'success'], 204);
    }
    public function handleFormActions(Request $request) {
        $action = $request->input('action');
        $ids = $request->input('selectedIds', []);
        $targetId = $request->input('targetId');

        switch ($action) {
            case 'delete':
                User::destroy($ids);
                return response()->json(['message' => 'success'], 200);
            case 'restore':
                User::onlyTrashed()->whereIn('id', $ids)->restore();
                return response()->json(['message' => 'success'], 200);
            case 'forceDelete':
                User::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                return response()->json(['message' => 'success'], 204);
            case 'setRole':
                if (!$targetId) {
                    return response()->json(['message' => 'Role ID is required'], 400);
                }
                if (!Role::where('id', $targetId)->exists()) {
                    return response()->json(['message' => 'Role not found'], 400);
                }
                User::whereIn('id', $ids)->update(['role_id' => $targetId]);
                return response()->json(['message' => 'success'], 200);
            case 'removeRole':
                User::whereIn('id', $ids)->update(['role_id' => null]);
                return response()->json(['message' => 'success'], 200);
            case 'setStatus':
                User::whereIn('id', $ids)->update(['status' => $targetId]);
                return response()->json(['message' => 'success'], 200);
            default:
                return response()->json(['message' => 'Action is invalid'], 400);
        }
    }
}
