<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    private int $page = 1;
    private int $limit = 10;
    private int $offset = 0;
    private string $keyword = "";

    public function getAllColor(Request $request)
    {
        try {
            $query = [
                "page" => $request->query('page', $this->page),
                "limit" => $request->query('length', $this->limit),
                "offset" => $request->query('start', $this->offset),
                "keyword" => $request->query('keyword', $this->keyword),
            ];

            $colorQuery = Color::query();

            if ($query['keyword']) {
                $colorQuery->where('color_name', 'like', '%' . $query['keyword'] . '%');
            }

            $colors = $colorQuery->orderBy('created_at', 'desc')->paginate($query['limit']);

            return response()->json(['status' => true, 'message' => 'Get All Colors Successfully', 'data' => $colors], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Get All Colors Error : ' . $th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }

    // List Color
    public function getListColor(Request $request)
    {
        try {
            $query = [
                "page" => $request->query('page', $this->page),
                "limit" => $request->query('length', $this->limit),
                "offset" => $request->query('start', $this->offset),
                "keyword" => $request->query('keyword', $this->keyword),
            ];

            $colorQuery = Color::query();

            if ($query['keyword']) {
                $colorQuery->where('name', 'like', '%' . $query['keyword'] . '%');
            }

            $colors = $colorQuery->paginate($query['limit']);

            return response()->json(['status' => true, 'message' => 'Get List Color Successfully', 'data' => $colors], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Get List Colors Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOneColor(Request $request, $id)
    {
        try {
            $color = Color::where('id', $id)->first();

            return response()->json(['status' => true, 'message' => 'Get One Color Successfully', 'data' => $color], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Get One Color Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createColor(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'color_name' => 'required|unique:colors,color_name',
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => false, 'message' => $validate->errors()], Response::HTTP_BAD_REQUEST);
            }

            $validated = $validate->validated();

            $data = [
                'color_name' => strtolower($validated['color_name']),
            ];

            $color = Color::create($data);

            return response()->json(['status' => true, 'message' => 'Create Color Successfully', 'data' => $color], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Create Color Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteColor(Request $request, $id)
    {
        try {
            $color = Color::where('id', $id)->first();

            if (!$color) {
                return response()->json(['status' => false, 'message' => 'Color Not Found'], Response::HTTP_NOT_FOUND);
            }

            $color->delete();

            return response()->json(['status' => true, 'message' => 'Delete Color Successfully'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Delete Color Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
