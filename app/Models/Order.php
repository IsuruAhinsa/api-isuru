<?php

namespace App\Models;

use App\Helper\OrderManager;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const STATUS_PENDING = 1;
    public const STATUS_PROCESSED = 2;
    public const STATUS_COMPLETED = 3;
    public const PAYMENT_STATUS_PAID = 1;
    public const PAYMENT_STATUS_PARTIALLY_PAID = 2;
    public const PAYMENT_STATUS_UNPAID = 3;

    public const SHIPMENT_STATUS_COMPLETED = 1;

    /**
     * @param array $input
     * @return array
     * @throws Exception
     */
    public function placeOrder(array $input)
    {
        $order_data = $this->prepareData($input);

        if (isset($order_data['error_description'])) {
            return $order_data;
        }
        $order = self::query()->create($order_data['order_data']);
        (new OrderDetail())->storeOrderDetails($order_data['order_details_data'], $order);
        (new Transaction())->storeTransaction($input, $order);
    }

    /**
     * @param array $input
     * @return array
     * @throws Exception
     */
    private function prepareData(array $input): array
    {
        $order_data = OrderManager::handleOrderData($input);

        if (isset($order_data['error_description'])) {
            return $order_data;
        } else {
            $order = [
                'customer_id' => $input['order_summery']['customer_id'],
                'sales_manager_id' => auth()->user()->id,
                'shop_id' => auth()->user()->shop_id,
                'sub_total' => $order_data['sub_total'],
                'discount' => $order_data['discount'],
                'total' => $order_data['total'],
                'quantity' => $order_data['quantity'],
                'paid_amount' => $input['order_summery']['paid_amount'],
                'due_amount' => $input['order_summery']['due_amount'],
                'order_status' => self::STATUS_PENDING,
                'order_number' => OrderManager::generateOrderNumber(auth()->user()->shop_id),
                'payment_method_id' => $input['order_summery']['payment_method_id'],
                'payment_status' => OrderManager::decidePaymentStatus($order_data['total'], $input['order_summery']['paid_amount']),
                'shipment_status' => self::SHIPMENT_STATUS_COMPLETED,
            ];

            return ['order_data' => $order, 'order_details_data' => $order_data['order_details']];
        }
    }

    final public function getAllOrders(array $input): LengthAwarePaginator
    {
        $is_admin = Auth::guard('admin')->check();

        $query = self::query()->with(['customer:id,name,phone', 'payment_method:id,name', 'sales_manager:id,name,phone', 'shop:id,company']);

        if (!$is_admin) {
            $query->where('shop_id', auth()->user()->shop_id);
        }

        $per_page = $input['per_page'] ?? 5;

        return $query->paginate($per_page);
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsTo
     */
    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * @return BelongsTo
     */
    public function sales_manager(): BelongsTo
    {
        return $this->belongsTo(SalesManager::class);
    }

    /**
     * @return BelongsTo
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * @return HasMany
     */
    public function order_details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
