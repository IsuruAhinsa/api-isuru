<?php

namespace App\Helper;

use Carbon\Carbon;

class PriceManager
{
    public const CURRENCY_SYMBOL = 'LKR ';
    public const CURRENCY_NAME = 'SRI LANKAN RUPEES ';

    /**
     * @param int $price
     * @param int $discount_percentage
     * @param int $discount_fixed
     * @param string $discount_start_date
     * @param string $discount_end_date
     * @return array
     */
    public static function calculate_sell_price(
        int    $price,
        int    $discount_percentage,
        int    $discount_fixed,
        string $discount_start_date,
        string $discount_end_date
    ): array
    {
        $discount = 0;
        if (!empty($discount_start_date) && !empty($discount_end_date)) {
            // checking the discount is currently active
            if (Carbon::now()->isBetween(Carbon::create($discount_start_date), Carbon::create($discount_end_date))) {
                return self::calculate_price($price, $discount_percentage, $discount_fixed);
            }
        }

        return ['price' => $price - $discount, 'discount' => $discount, 'symbol' => self::CURRENCY_SYMBOL];
    }

    /**
     * @param int $price
     * @param int $discount_percentage
     * @param int $discount_fixed
     * @return array
     */
    protected static function calculate_price(
        int $price,
        int $discount_percentage,
        int $discount_fixed,
    ): array
    {
        $discount = 0;
        if (!empty($discount_percentage)) {
            $discount = ($price * $discount_percentage) / 100;
        }
        if (!empty($discount_fixed)) {
            $discount += $discount_fixed;
        }

        return ['price' => $price - $discount, 'discount' => $discount, 'symbol' => self::CURRENCY_SYMBOL];
    }
}
