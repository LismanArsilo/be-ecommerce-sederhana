<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private int $page = 1;
    private int $limit = 10;
    private int $offset = 0;
    private string $keyword = "";
    private string $category = "";
    public function getAllProduct(Request $request)
    {
        try {
            $query = [
                "page" => $request->query('page', $this->page),
                "limit" => $request->query('length', $this->limit),
                "offset" => $request->query('start', $this->offset),
                "keyword" => $request->query('keyword', $this->keyword),
            ];
            $productsQuery = Product::query();

            if ($query['keyword']) {
                $productsQuery->where('name', 'like', '%' . $query['keyword'] . '%');
            }

            $products = $productsQuery->orderBy('created_at', 'desc')->with(['category', 'images'])->paginate($query['limit']);

            return response()->json(['status' => true, 'message' => 'Get all Products Successfully', 'data' => $products], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Get All Products Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOneProduct(Request $request, $id)
    {
        try {
            $product = Product::with(['category', 'colors', 'sizes', 'images'])->where('id', $id)->first();

            return response()->json(['status' => true, 'message' => 'Get One Product Successfully', 'data' => $product], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Get One Product Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createProduct(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'category' => 'required',
                'name' => 'required|min:3|max:255|unique:products,name',
                'price' => 'required',
                'description' => 'required',
                'sizes' => 'required|array',
                'sizes.*' => 'required',
                'colors' => 'required|array',
                'colors.*' => 'required',
            ]);

            if ($validate->fails()) {
                $errors = $validate->errors()->toArray();

                $transformedErrors = collect($errors)->mapWithKeys(function ($message, $key) {
                    return [$key => $message[0]];
                })->toArray();

                return response()->json(['status' => false, 'message' => 'Validation Failed', 'data' => $transformedErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $validated = $validate->validated();

            $data = [
                'cate_id' => $validated['category'],
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'],
            ];

            $product = Product::create($data);

            $productAttach = Product::with(['category', 'sizes', 'colors'])->where('id', $product->id)->first();
            $productAttach->sizes()->attach($request->sizes);
            $productAttach->colors()->attach($request->colors);

            return response()->json(['status' => true, 'message' => 'Create Product Successfully', 'data' => $productAttach]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Create Product Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->sizes()->detach();
            $product->colors()->detach();

            $product->delete();

            return response()->json(['status' => true, 'message' => 'Delete Product Successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Delete Product Error: ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'cate_id' => 'required',
                'name' => 'required|min:3|max:255',
                'price' => 'required',
                'description' => 'required',
            ]);

            if ($validate->fails()) {
                $errors = $validate->errors()->toArray();

                $transformedErrors = collect($errors)->mapWithKeys(function ($message, $key) {
                    return [$key => $message[0]];
                });

                return response()->json(['status' => false, 'message' => 'Validation Failed', 'data' => $transformedErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $validated = $validate->validated();

            $product = Product::findOrFail($id);

            $product->fill($request->all());

            return response()->json(['status' => true, 'message' => 'Update Product Successfully', 'data' => $product], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Update Product Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllProductUser(Request $request)
    {
        try {
            $query = [
                "page" => $request->query('page', $this->page),
                "limit" => $request->query('length', $this->limit),
                "offset" => $request->query('start', $this->offset),
                "keyword" => $request->query('keyword', $this->keyword),
                "category" => $request->query('category', $this->category)
            ];

            $productQuery = Product::query();

            if ($query['keyword']) {
                $productQuery->where('name', 'like', '%' . $query['keyword'] . '%');
            }

            if ($query['category']) {
                $productQuery->where('cate_id', $query['category']);
            }

            $products = $productQuery->orderBy('created_at', 'desc')->with(['category', 'images'])->paginate($query['limit']);

            return response()->json(['status' => true, 'message' => 'Get all Products Successfully', 'data' => $products], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
