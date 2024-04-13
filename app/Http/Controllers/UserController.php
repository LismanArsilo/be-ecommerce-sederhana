<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getAllUser()
    {
        try {
            $user = User::all();
            return response()->json(['status' => true, 'message' => 'Get All User Successfully', 'data' => $user], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => true, 'message' => 'Get All User Error :' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOneUser($id)
    {
        try {
            $user = User::where('id', $id)->first();
            Log::debug($user);
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['status' => true, 'message' => 'Get One User Successfully', 'data' => $user], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255',
                'password' => 'nullable|string|min:8',
            ]);

            $validated = $validate->validated();

            $user = User::where('id', $id)->first();

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $user->name = $request->$validated['name'];
            $user->name = $request->$validated['email'];
            $user->save();

            return response()->json(['status' => true, 'message' => 'Update User Successfully', 'data' => $user], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::where('id', $id)->first();

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $user->delete();

            return response()->json(['status' => true, 'message' => 'Delete User Successfully', 'data' => $user], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
