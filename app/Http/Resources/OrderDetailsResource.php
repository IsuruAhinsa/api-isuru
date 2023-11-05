<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->payment_status == Order::PAYMENT_STATUS_PAID) {
            $payment_status = 'PAID';
        } elseif ($this->payment_status == Order::PAYMENT_STATUS_PARTIALLY_PAID) {
            $payment_status = 'PARTIALLY_PAID';
        } else {
            $payment_status = 'UNPAID';
        }

        if ($this->order_status == Order::STATUS_PENDING) {
            $order_status = 'PENDING';
        } elseif ($this->order_status == Order::STATUS_PROCESSED) {
            $order_status = 'PROCESSED';
        } else {
            $order_status = 'COMPLETED';
        }

        return [
            'id' => $this->id,
            'customer' => new CustomerResource($this->customer),
            'payment_method' => new PaymentMethodResource($this->payment_method),
            'sales_manager' => new SalesManagerResource($this->sales_manager),
            'shop' => new ShopResource($this->shop),
            'transactions' => TransactionResource::collection($this->transactions),
            'order_number' => $this->order_number,
            'order_status' => $order_status,
            'payment_status' => $payment_status,
            'discount' => $this->discount,
            'due_amount' => $this->due_amount,
            'paid_amount' => $this->paid_amount,
            'quantity' => $this->quantity,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'order_details' => OrderInfoResource::collection($this->order_details),
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet.',
        ];
    }
}
