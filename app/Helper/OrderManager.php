<?php

namespace App\Helper;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Exception;

class OrderManager
{
    private const ORDER_PREFIX = 'FBD';

    /**
     * @param int $shop_id
     * @return string
     * @throws Exception
     */
    public static function generateOrderNumber(int $shop_id): string
    {
        return self::ORDER_PREFIX . $shop_id . Carbon::now()->format('dmy') . random_int(100, 999);
    }

    /**
     * @param array $input
     * @return array
     */
    public static function handleOrderData(array $input): array
    {
        $sub_total = 0;
        $total = 0;
        $discount = 0;
        $quantity = 0;
        $order_details = [];

        if (isset($input['carts'])) {
            foreach ($input['carts'] as $key => $cart) {

                $product = (new Product())->getProductById($key);

                if ($product && $product->stock >= $cart['quantity']) {

                    $price = PriceManager::calculate_sell_price($product->price, $product->discount, $product->discount_fixed, $product->discount_start, $product->discount_end);
                    $discount += $price['discount'] * $cart['quantity'];
                    $quantity += $cart['quantity'];
                    $sub_total += $product->price * $cart['quantity'];
                    $total += $price['price'] * $cart['quantity'];

                    // update product stock when ordering products
                    $product_data['stock'] = $product->stock - $cart['quantity'];
                    $product->update($product_data);
                    // add quantity attribute
                    $product->quantity = $cart['quantity'];
                    $order_details[] = $product;
                } else {
                    info('PRODUCT_STOCK_OUT', ['product' => $product, 'cart' => $cart]);
                    return ['error_description' => $product->name . ' stock out or not exist'];
                    break;
                }
            }
        }

        return [
            'sub_total' => $sub_total,
            'total' => $total,
            'discount' => $discount,
            'quantity' => $quantity,
            'order_details' => $order_details,
        ];
    }

    /**
     * @param float $amount
     * @param float $paid_amount
     * @return int
     */
    public static function decidePaymentStatus(float $amount, float $paid_amount): int
    {
        if ($amount <= $paid_amount) {
            $payment_status = Order::PAYMENT_STATUS_PAID;
        } elseif ($paid_amount <= 0) {
            $payment_status = Order::PAYMENT_STATUS_UNPAID;
        } else {
            $payment_status = Order::PAYMENT_STATUS_PARTIALLY_PAID;
        }

        return $payment_status;
    }
}
