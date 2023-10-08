<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class SalesManager extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    public const IMAGE_UPLOAD_PATH = 'images/uploads/sales_manager/';
    public const THUMB_IMAGE_UPLOAD_PATH = 'images/uploads/sales_manager_thumb/';
    public const NIC_IMAGE_UPLOAD_PATH = 'images/uploads/sales_manager/nic/';
    public const NIC_THUMB_IMAGE_UPLOAD_PATH = 'images/uploads/sales_manager_thumb/nic/';
    const STATUS_ACTIVE = TRUE;
    const STATUS_INACTIVE = FALSE;

    protected $fillable = [
        'user_id', 'shop_id', 'name', 'email', 'phone', 'status', 'password', 'nic', 'photo', 'nic_photo', 'bio',
    ];

    /**
     * @return MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * @param array $input
     * @return array
     */
    final public function prepareData(array $input): array
    {
        $sales_manager['user_id'] = auth()->id();
        $sales_manager['shop_id'] = $input['shop'] ?? 0;
        $sales_manager['name'] = $input['name'] ?? null;
        $sales_manager['email'] = $input['email'] ?? null;
        if (!empty($input['password'])) {
            $sales_manager['password'] = $input['password'] ? Hash::make($input['password']) : Hash::make('password');
        }
        $sales_manager['phone'] = $input['phone'] ?? null;
        $sales_manager['status'] = $input['status'] ?? 1;
        $sales_manager['nic'] = $input['nic'] ?? null;
        $sales_manager['bio'] = $input['bio'] ?? null;

        return $sales_manager;
    }

    /**
     * @param array $input
     * @return LengthAwarePaginator
     */
    final public function getAllSalesManagers(array $input): LengthAwarePaginator
    {
        $query = self::query()->with(
            'address',
            'address.province:id,name_en,name_si',
            'address.district:id,name_en,name_si',
            'address.city:id,name_en,name_si',
            'user:id,name',
            'shop',
        );

        $per_page = $input['per_page'] ?? 5;

        if (!empty($input['search'])) {
            $query->where('name', 'like', '%' . $input['search'] . '%')
                ->orWhere('phone', 'like', '%' . $input['search'] . '%')
                ->orWhere('nic', 'like', '%' . $input['search'] . '%')
                ->orWhere('email', 'like', '%' . $input['search'] . '%');
        }

        if (!empty($input['order_by'])) {
            $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }

        return $query
            ->paginate($per_page);

    }

    final public function getUserByEmailOrPhone(array $input)
    {
        return self::query()->where('email', $input['email'])
            ->orWhere('phone', $input['email'])
            ->first();
    }
}
