<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Nasabah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'] ?? 'nasabah',
                    'email_verified_at' => now(),
                ]);

                if ($user->role === 'nasabah') {
                    Nasabah::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $data['nama'],
                        'nik' => $data['nik'],
                        'nomor_telepon' => $data['nomor_telepon'],
                        'alamat_ktp' => $data['alamat_ktp'],
                        'email' => $data['email'],
                        'jenis_kelamin' => $data['jenis_kelamin'],
                        'status_akun' => 'Aktif',
                    ]);
                }

                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'message' => 'Registrasi berhasil.',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => new UserResource($user),
                ], 201);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan internal saat registrasi.',
                'errors' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan sistem.',
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan catatan kami.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Berhasil logout.'], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal logout.',
                'errors' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan sistem.',
            ], 500);
        }
    }
}
