<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function createImageProduct(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'fileList' => 'required|array',
                'fileList.*.size' => 'required|numeric|max:2097152', // maksimum 2048 KB atau 2MB
            ]);

            if ($validate->fails()) {
                $errors = $validate->errors()->toArray();

                $transformedError = collect($errors)->mapWithKeys(function ($message, $key) {
                    return [$key => $message[0]];
                })->toArray();

                return response()->json(['status' => false, 'message' => 'Validation Failed', 'data' => $transformedError], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $validated = $validate->validated();

            $product = Product::where('id', $id)->first();

            if (!$product) {
                return response()->json(['status' => false, 'message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            if ($request['fileList']) {
                foreach ($request['fileList'] as $file) {
                    $originalName = $file['name'];
                    $replace = str_replace(' ', '-', $originalName);
                    $pathName = $id . '-' . now()->timestamp . '-' . $file['lastModified'] .  '-' . $replace;
                    $file['originFileObj']->storeAs('public/products/', $pathName);

                    $data = [
                        'prod_id' => $product->id,
                        'image_name' => $pathName
                    ];

                    $image = Image::create($data);
                }
            }

            $productUpdate = Image::where('prod_id', $id)->get();

            return response()->json(['status' => true, 'message' => 'Upload Image Product Successfully', 'data' => $productUpdate], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Create Image Product Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getImageProduct(Request $request, $image)
    {
        try {
            $image = Image::where('image_name', $image)->first();

            if (!$image) {
                return response()->json(['status' => false, 'message' => 'Image not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['status' => true, 'message' => 'Get Image Product Successfully', 'data' => $image], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Get Image Product Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
