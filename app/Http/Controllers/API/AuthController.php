<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Autetinification API"
 * )
 *
 * @OA\Schema(
 *    schema="UserSchema",
 *        @OA\Property(
 *            property="id",
 *            description="User identifier",
 *            type="integer",
 *            nullable="false",
 *            example="1"
 *        ),
 *        @OA\Property(
 *            property="name",
 *            description="User name",
 *            type="string",
 *            nullable="false",
 *            example="admin"
 *        ),
 *        @OA\Property(
 *            property="email",
 *            description="User E-mail",
 *            type="string",
 *            nullable="false",
 *            example="kellen.boyer@example.com"
 *        ),
 *        @OA\Property(
 *            property="role",
 *            description="User role",
 *            type="string",
 *            nullable="false",
 *            example="user"
 *        ),
 *        @OA\Property(
 *            property="updated_at",
 *            description="User updated date",
 *            type="string",
 *            nullable="false",
 *            example="2023-11-15T05:34:17.000000Z"
 *        ),
 *        @OA\Property(
 *            property="created_at",
 *            description="User created date",
 *            type="string",
 *            nullable="false",
 *            example="2023-11-15T05:34:17.000000Z"
 *        ),
 *    )
 * )
 */
class AuthController extends Controller
{

    /**
     * Handle an incoming authentication request.
     *
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Authenticate user and generate Bearer token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     format="string",
     *                     type="string",
     *                     description="User's email",
     *                 ),
     *                 @OA\Property(
     *                    property="password",
     *                    format="string",
     *                     type="string",
     *                     description="User's password",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Login successful",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserSchema"
     *         )
     *     ),
     *     @OA\Response(response="402", description="Invalid credentials")
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            $user['token'] = $user->createToken('authToken')->accessToken;
            return response()->json([
                'user' => $user
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid credentials'
        ], 402);
    }

    /**
     * Handle an incoming registration request.
     *
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     format="string",
     *                     type="string",
     *                     description="User's name",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     format="string",
     *                     type="string",
     *                     description="User's email",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     format="string",
     *                     type="string",
     *                     description="User's password",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User created successfully",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  description="User created successfully",
     *                  type="string",
     *                  nullable="false",
     *                  example="User created successfully"
     *              ),
     *              @OA\Property(
     *                  property="token",
     *                  description="Token",
     *                  type="string",
     *                  nullable="false",
     *              ),
     *              @OA\Property(
     *                  property="user",
     *                  description="User data",
     *                  ref="#/components/schemas/UserSchema"
     *              ),
     *         )
     *     ),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $user->createToken('authToken')->accessToken
        ]);
    }

    /**
     * Handle an incoming logout request.
     *
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Clears user tokens",
     *     @OA\Response(response="200", description="Successfully logged out")
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

}
