<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'account_number'];

    /**
     * @return array|Collection
     */
    final public function getPaymentMethods(): array|Collection
    {
        return self::query()->select(['id', 'name', 'status', 'account_number'])->get();
    }
}
