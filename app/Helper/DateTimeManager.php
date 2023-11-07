<?php

namespace App\Helper;

use Carbon\Carbon;

class DateTimeManager
{
    /**
     * @param string|null $discount_end
     * @return int
     */
    public static function calculateDiscountRemainingDays(string|null $discount_end): int
    {
        $discount_remaining_days = 0;
        if ($discount_end != null) {
            $discount_remaining_days = Carbon::now()->diffInDays(Carbon::create($discount_end));
        }

        return $discount_remaining_days;
    }
}
