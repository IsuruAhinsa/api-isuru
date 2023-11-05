<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = (new Order())->getAllOrders($request->all());
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            (new Order())->placeOrder($request->all());
            DB::commit();
            return response()->json(['msg' => 'Order Placed Successfully!', 'flag' => TRUE]);
        } catch (\Throwable $throwable) {
            info('ORDER_PLACED_FAILED', ['message' => $throwable->getMessage(), $throwable]);
            DB::rollBack();
            return response()->json(['msg' => $throwable->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load([
            'customer',
            'payment_method:id,name',
            'sales_manager:id,name',
            'shop',
            'order_details',
            'transactions',
            'transactions.customer',
            'transactions.payment_method',
            'transactions.transactionable',
        ]);
        return new OrderDetailsResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
