<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    private int $page = 1;
    private int $limit = 10;
    private int $offset = 0;
    private string $keyword = "";

    public function getAllSize(Request $request)
    {
        try {
            $query = [
                'page' => $request->query('page', $this->page),
                'limit' => $request->query('limit', $this->limit),
                'offset' => $request->query('offset', $this->offset),
                'keyword' => $request->query('keyword', $this->keyword)
            ];

            $sizeQuery = Size::query();

            if (!empty($query['keyword'])) {
                $sizeQuery->where('size_name', 'LIKE', '%' . $query['keyword'] . '%');
            }

            $sizes = $sizeQuery->orderBy('created_at', 'desc')->paginate($query['limit']);

            return response()->json(['status' => true, 'message' => 'Get All Size Successfully', 'data' => $sizes], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Get All Size Error' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOneSize(Request $request, $id)
    {
        try {
            $size = Size::findOrFail($id);

            return response()->json(['status' => false, 'message' => 'Get One Size Successfuly', 'data' => $size], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Get One Size Error :' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createSize(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'size_name' => 'required|string|unique:sizes,size_name'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validate->errors()
                ], Response::HTTP_BAD_REQUEST);
            }

            $validated = $validate->validated();

            $data = [
                'size_name' => strtoupper(strtolower($validated['size_name']))
            ];

            $size = Size::create($data);

            return response()->json(['status' => true, 'message' => 'Create Size Successfuly', 'data' => $size], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Create Size Error :' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteSize(Request $request, $id)
    {
        try {
            $size = Size::findOrFail($id);
            $size->delete();

            return response()->json(['status' => true, 'message' => 'Delete Size Successfuly'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Delete Size Error :' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getListSize(Request $request)
    {
        try {
            $query = [
                'page' => $request->query('page', $this->page),
                'limit' => $request->query('limit', $this->limit),
                'offset' => $request->query('offset', $this->offset),
                'keyword' => $request->query('keyword', $this->keyword)
            ];

            $sizeQuery = Size::query();

            if (!empty($query['keyword'])) {
                $sizeQuery->where('name', 'LIKE', '%' . $query['keyword'] . '%');
            }

            $sizes = $sizeQuery->paginate($query['limit']);

            return response()->json(['status' => true, 'message' => 'Get List Size Successfully', 'data' => $sizes], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => "Get List Size Error : " . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
