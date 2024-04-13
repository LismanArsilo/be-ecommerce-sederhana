<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    private int $page = 1;
    private int $limit = 5;
    private int $offset = 0;
    private string $keyword = "";

    public function getAllCategory(Request $request)
    {
        try {
            $query = [
                "page" => $request->query('page', $this->page),
                "limit" => $request->query('length', $this->limit),
                "offset" => $request->query('start', $this->offset),
                "keyword" => $request->query('keyword', $this->keyword),
            ];
            $categoryQuery = Category::query();

            if ($query['keyword']) {
                $categoryQuery->where('name', 'like', '%' . $query['keyword'] . '%');
            }

            $categories = $categoryQuery->paginate($query['limit']);

            return response()->json(['status' => true, 'message' => 'Get All Category Successfully', 'data' => $categories], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function createCategory(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validate Failed',
                    'errors' => $validate->errors()
                ], Response::HTTP_BAD_REQUEST);
            }

            $validated = $validate->validated();

            $data = [
                'name' => ucwords(strtolower($validated['name']))
            ];

            $category = Category::create($data);

            return response()->json(['status' => true, 'message' => 'Create Category Successfully', 'data' => $category], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOneCategory($id)
    {
        try {
            $category = Category::where('id', $id)->first();

            if (!$category) return response()->json(['status' => false, 'message' => 'Category not found'], Response::HTTP_NOT_FOUND);

            return response()->json(['status' => true, 'message' => 'Get One Successfully', 'data' => $category], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::where('id', $id)->first();

            if (!$category) return response()->json(['status' => false, 'message' => 'Category not found'], Response::HTTP_NOT_FOUND);

            $category->delete();

            return response()->json(['status' => true, 'message' => 'Category Delete Successfully'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Api User
    public function getAllCategoryUser(Request $request)
    {
        try {
            $query = [
                "keyword" => $request->query('keyword', $this->keyword),
            ];

            $categoryQuery = Category::query();


            if ($query['keyword']) {
                $categoryQuery->where('name', 'like', '%' . $query['keyword'] . '%');
            }

            $categories = $categoryQuery->get();

            return response()->json(['status' => true, 'message' => 'Get All Categories For User Successfully', 'data' => $categories], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
