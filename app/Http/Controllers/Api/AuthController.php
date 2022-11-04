<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Carbon;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = array_merge($request->only(['email', 'password']), ['active' => 1]);

        if (!auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Acesso negado'], 401);
        }

        $token = null;
        $multi = null;
        if ($request->lembrar === false) {
            $token = auth('api')->attempt($credentials);
            $multi = 60;

        } else if ($request->lembrar === true) {
            $token = JWTAuth::attempt($credentials, ['exp' => Carbon\Carbon::now()->addDays(7)->timestamp]);
            $multi = 10080;
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * $multi,
            'lembrar' => $request->lembrar
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout efetuado com sucesso']);
    }
}
