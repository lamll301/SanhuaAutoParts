<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if (User::where('username', $request->username)->exists()) {
            return response()->json([
                'message' => 'Tên tài khoản đã tồn tại. Vui lòng chọn tên tài khoản khác.'
            ], 422);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email đã tồn tại. Vui lòng chọn email khác.'
            ], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $token = auth('api')->login($user);

        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Thông tin đăng nhập không chính xác. Vui lòng kiểm tra lại.'], 422
            );
        }

        if (auth('api')->user()->status === User::STATUS_BANNED) {
            return response()->json([
                'message' => 'Tài khoản đã bị cấm. Vui lòng liên hệ quản trị viên.'
            ], 400);
        }

        return $this->respondWithToken($token);
    }
    
    public function me()
    {
        $user = auth('api')->user();
        return response()->json($user->load(['avatar:id,path']));
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'success']);
    }

    public function refresh()
    {
        auth('api')->factory()->setTTL(config('jwt.refresh_ttl'));
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function redirectToProvider($provider) 
    {
        $redirectUrl = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();
        return response()->json([
            'url' => $redirectUrl
        ]);
    }

    public function handleProviderCallback($provider) 
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $user = User::where('email', $socialUser->email)->first();
            if (!$user) {
                $user = User::create([
                    'email' => $socialUser->email,
                    'username' => $provider . time(),
                    'password' => Hash::make(Str::random(16)),
                    'name' => $socialUser->name,
                ]);
                if ($socialUser->avatar) {
                    $storagePath = "User/{$user->id}";
                    $file = file_get_contents($socialUser->avatar);
                    $filename = 'avatar_' . time() . '.jpg';
                    $fullPath = $storagePath . '/' . $filename;
                    Storage::disk('public')->put($fullPath, $file);
                    $image = $user->avatar()->create([
                        'path' => Storage::url($fullPath),
                        'filename' => $filename,
                        'mime_type' => 'image/jpeg',
                        'size' => strlen($file),
                    ]);
                    $user->avatar_id = $image->id;
                    $user->save();
                }
            }
            $token = auth('api')->login($user);
            return redirect()->away(env('APP_FE_URL') . "?token={$token}");
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 422);
        }
    }
}