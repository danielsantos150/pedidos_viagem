<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return ResponseHelper::formatResponse(
            compact('user', 'token'),
            "Usuario criado com sucesso.", 
            Response::HTTP_CREATED
        );
    }

    /**
     * Log in the user and return a token.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return ResponseHelper::formatResponse(
            compact('token'),
            "Usuario autenticado com sucesso.", 
            Response::HTTP_OK
        );
    }

    /**
     * Log out the user (invalidate the token).
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return ResponseHelper::formatResponse(
            [],
            "Usuario deslogado com sucesso.",
            Response::HTTP_OK
        );
    }
}
