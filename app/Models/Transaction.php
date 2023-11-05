<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const CREDIT = 1;
    public const DEBIT = 2;
    public const STATUS_SUCCESS = TRUE;
    public const STATUS_FAILED = FALSE;

    /**
     * @return MorphTo
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param array $input
     * @param $order
     * @return Builder|Model
     */
    public function storeTransaction(array $input, $order): Model|Builder
    {
        $transaction_data = $this->prepareData($input, $order);
        return self::query()->create($transaction_data);
    }

    /**
     * @param array $input
     * @param $order
     * @return array
     */
    private function prepareData(array $input, $order): array
    {
        return [
            'trx_id' => $input['order_summery']['trx_id'],
            'order_id' => $order->id ?? 0,
            'customer_id' => $input['order_summery']['customer_id'],
            'payment_method_id' => $input['order_summery']['payment_method_id'],
            'amount' => $input['order_summery']['paid_amount'],
            'status' => self::STATUS_SUCCESS,
            'transaction_type' => self::CREDIT,
            'transactionable_type' => SalesManager::class,
            'transactionable_id' => auth()->user()->id,
        ];
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
}
