<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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

        return [
            'id' => $this->id,
            'customer_name' => $this->customer?->name,
            'customer_phone' => $this->customer?->phone,
            'payment_method' => $this->payment_method?->name,
            'sales_manager_name' => $this->sales_manager?->name,
            'sales_manager_phone' => $this->sales_manager?->phone,
            'shop' => $this->shop?->company,
            'order_number' => $this->order_number,
            'order_status' => $this->order_status,
            'payment_status' => $payment_status,
            'discount' => $this->discount,
            'due_amount' => $this->due_amount,
            'paid_amount' => $this->paid_amount,
            'quantity' => $this->quantity,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'created_at' => $this->created_at->toDayDateTimeString(),
            'updated_at' => $this->created_at != $this->updated_at ? $this->updated_at->toDayDateTimeString() : 'Not updated yet.',
        ];
    }
}
