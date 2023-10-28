<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\ShopResource;
use App\Models\SalesManager;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public const USER_ADMINISTRATOR = 1;
    public const USER_SALES_MANAGER = 2;

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    final public function login(AuthRequest $request): JsonResponse
    {
        if ($request->input('user_type') == self::USER_ADMINISTRATOR) {
            $user = (new User())->getUserByEmailOrPhone($request->all());
            $role = self::USER_ADMINISTRATOR;
        } else {
            $user = (new SalesManager())->getUserByEmailOrPhone($request->all());
            $role = self::USER_SALES_MANAGER;
        }

        if ($user && Hash::check($request->input('password'), $user->password)) {
            $branch = null;
            if ($role == self::USER_SALES_MANAGER) {
                $branch = (new Shop())->getShopDetailsById($user->shop_id);
            }
            $user_data['token'] = $user->createToken($user->email)->plainTextToken;
            $user_data['name'] = $user->name;
            $user_data['email'] = $user->email;
            $user_data['phone'] = $user->phone;
            $user_data['photo'] = $user->photo;
            $user_data['role'] = $role;
            $user_data['branch'] = $branch ? new ShopResource($branch) : null;

            return response()->json($user_data);
        }

        throw ValidationException::withMessages([
            'email' => ['The Provided credentials are incorrect.']
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json(['msg' => 'You have successfully logged out!']);
    }
}
