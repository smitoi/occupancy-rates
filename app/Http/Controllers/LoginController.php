<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"authentication"},
     *     description="Login",
     *     summary="Verifies the credentials and provides a bearer token",
     *     operationId="login",
     *     requestBody={"$ref": "#/components/requestBodies/LoginRequest"},
     *     @OA\Response(
     *         response="200",
     *         description="Success with a valid auth token",
     *         @OA\JsonContent(
     *             example={
     *                  "success": true,
     *                  "data": {
     *                      "token": "valid-auth-token"
     *                  }
     *             },
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Failed if the credentials are not valid",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Invalid credentials provided"
     *             }
     *         )
     *     )
     * )
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = User::query()->where(
            'email', Arr::get($credentials, 'email')
        )->first();

        if ($user && Auth::attempt($credentials)) {
            return response()->json([
                'token' => $user->createToken($request->getClientIp())->plainTextToken,
            ]);
        }

        return response()->json(
            data: [
                'message' => 'Invalid credentials provided',
            ],
            status: 400
        );
    }
}


