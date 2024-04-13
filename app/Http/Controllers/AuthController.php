<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function helloTest()
    {
        return response()->json(['status' => 'ok'], Response::HTTP_OK);
    }

    public function registerUser(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string',
                'confirmPassword' => 'required|string'
            ]);

            if ($validate->fails()) {
                $errors = $validate->errors()->toArray();

                $transformedErrors = collect($errors)->mapWithKeys(function ($message, $key) {
                    return [$key => $message[0]];
                })->toArray();

                return response()->json(['status' => false, 'message' => 'Validation Failed', 'data' => $transformedErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $validated = $validate->validated();

            if ($validated['password'] !== $validated['confirmPassword']) {
                return response()->json(['status' => false, 'message' => 'Password Not Match'], Response::HTTP_FAILED_DEPENDENCY);
            }

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => 2
            ];

            $user = User::create($data);

            return response()->json(['status' => true, 'message' => 'Register Successfully', 'data' => $user], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);


            if ($validate->fails()) {
                $errors = $validate->errors()->toArray();

                $transformedErrors = collect($errors)->mapWithKeys(function ($message, $key) {
                    return [$key => $message[0]];
                })->toArray();

                return response()->json(['status' => false, 'message' => 'Validation Failed', 'data' => $transformedErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $validated = $validate->validated();

            Log::debug(Auth::user());
            if (!Auth::attempt($validated)) {
                return response()->json([
                    'message' => 'Please Cek Your Email Or Password'
                ], Response::HTTP_BAD_REQUEST);
            }

            $user = User::where('email', $validated['email'])->first();

            $expiresAt = now()->addHours(12);
            $token = $user->createToken('auth_token', ['*'], $expiresAt)->plainTextToken;

            $dataLogin = [
                "id" => $user->id,
                "name" => $user->name,
                "role" => $user->role_id,
            ];

            return response()->json(['status' => true, 'message' => 'Login Successfully', 'data' => $dataLogin, 'token' => $token], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logoutUser(Request $request)
    {
        try {
            $currentToken = $request->user()->currentAccessToken();

            $currentToken->delete();

            return response()->json(['status' => true, 'message' => 'Successfully logged out'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkTokenSession(Request $request)
    {
        try {
            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
                Log::debug($user);
                return response()->json(['status' => true, 'message' => 'Your Token Is Valid'], Response::HTTP_OK);
            }
            Log::debug('Not Valid');

            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
