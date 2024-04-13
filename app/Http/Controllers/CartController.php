<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function getOneCart(Request $request, $userId)
    {
        try {
            $cart = Cart::where('user_id', $userId)->first();

            if (!$cart) return response()->json(['status' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);

            return response()->json([
                'status' => true,
                'data' => $cart
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createCart(Request $request)
    {
        try {
            $validate = Validator::validate($request->all(), []);

            return response()->json(['status' => true, 'message' => 'Create Cart Successfuly', 'data' => 'ok'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllCartItems(Request $request, $userId)
    {
        try {
            $cart = Cart::where('user_id', $userId)->first();

            if (!$cart) {
                return response()->json(['status' => false, 'message' => 'Cart Not Found'], Response::HTTP_NOT_FOUND);
            }

            $cartItems = CartItem::where('cart_id', $cart->id)->with(['products', 'products.images', 'products.category'])->get();

            return response()->json([
                'status' => true,
                'data' => $cartItems
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function addProductToCart(Request $request, $userId)
    {
        try {
            $validate = Validator::make($request->all(), [
                'product_id' => 'required',
                'qty' => 'nullable'
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => false, 'message' => "Validate Failed", 'errors' => $validate->errors()], Response::HTTP_BAD_REQUEST);
            }

            $validated = $validate->validated();

            $cartUser = Cart::where('user_id', $userId)->first();

            if (!$cartUser) {
                $cart = Cart::create(['user_id' => $userId]);
            }

            if ($cartUser) {
                $cartItem = CartItem::where('cart_id', $cartUser->id)->where('product_id', $validated['product_id'])->first();
                if ($cartItem) {
                    return response()->json(['status' => false, 'message' => 'Item Already Exists, Silahkan Ambil Di Keranjang'], Response::HTTP_CONFLICT);
                }
            }

            $dataCartItem = [
                'product_id' => $validated['product_id'],
                'cart_id' => $cartUser->id ?? $cart->id,
                'qty' => $validated['qty'] ?? 1
            ];

            $cartItem = CartItem::create($dataCartItem);

            $cartData = CartItem::where('id', $cartItem->id)->with(['products', 'products.images', 'products.category'])->first();

            return response()->json(['status' => true, 'message' => 'Add Product To Cart Successfuly', 'data' => $cartData], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteProductCart(Request $request, $id)
    {
        try {
            $cartItem = CartItem::where('id', $id)->first();

            if (!$cartItem) {
                return response()->json(['status' => false, 'message' => 'Item Not Found'], Response::HTTP_NOT_FOUND);
            }

            $cartItem->delete();

            return response()->json(['status' => true, 'message' => 'Delete Item Successfully'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateQtyOrderItem(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'type' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => false, 'message' => "Validate Failed", 'errors' => $validate->errors()], Response::HTTP_BAD_REQUEST);
            }

            $validated = $validate->validated();

            $cartItem = CartItem::with(['products', 'products.images', 'products.category'])->where('id', $id)->first();


            if (!$cartItem) {
                return response()->json(['status' => false, 'message' => 'Item Not Found'], Response::HTTP_NOT_FOUND);
            }

            if ($validated['type'] == 'dec') {
                if ($cartItem->qty <= 1) {
                    return response()->json(['status' => false, 'message' => 'Qty Is Default 1'], Response::HTTP_OK);
                }
                $cartItem->qty = $cartItem->qty - 1;
                $cartItem->save();
            } else {
                $cartItem->qty = $cartItem->qty + 1;
                $cartItem->save();
            }

            return response()->json(['status' => true, 'message' => 'Update Qty Item Order Successfully', 'data' => $cartItem], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStatusOrderItem(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'status' => 'required'
            ]);

            if ($validate->fails()) {
                return response()->json(['status' => false, 'message' => "Validate Failed", 'errors' => $validate->errors()], Response::HTTP_BAD_REQUEST);
            }

            $validated = $validate->validated();

            $cartItem = CartItem::with(['products', 'products.images', 'products.category'])->where('id', $id)->first();
            $cartItem->status_order = $validated['status'];
            $cartItem->save();

            return response()->json(['status' => true, 'message' => 'Update Status Order Successfuly', 'data' => $cartItem], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error : ' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function countCartItems(Request $request, $userId)
    {
        try {
            $cart = Cart::where('user_id', $userId)->first();

            if (!$cart) return response()->json(['status' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);

            $countItem = CartItem::where('cart_id', $cart->id)->count();

            return response()->json(['status' => true, 'data' => $countItem], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Server Error :' . $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
