<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Pagination\LengthAwarePaginator;

class Shop extends Model
{
    use HasFactory;

    public const LOGO_UPLOAD_PATH = 'images/uploads/shop/';
    public const THUMB_LOGO_UPLOAD_PATH = 'images/uploads/shop_thumb/';
    const STATUS_ACTIVE = TRUE;
    const STATUS_INACTIVE = FALSE;

    protected $fillable = [
        'user_id',
        'company',
        'email',
        'phone',
        'description',
        'logo',
        'status',
    ];

    /**
     * @return MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * @param array $input
     * @return LengthAwarePaginator
     */
    final public function getAllShops(array $input): LengthAwarePaginator
    {
        $query = self::query()->with(
            'address',
            'address.province:id,name_en,name_si',
            'address.district:id,name_en,name_si',
            'address.city:id,name_en,name_si',
            'user:id,name',
        );

        $per_page = $input['per_page'] ?? 5;

        if (!empty($input['search'])) {
            $query->where('company', 'like', '%' . $input['search'] . '%')
                ->orWhere('phone', 'like', '%' . $input['search'] . '%')
                ->orWhere('email', 'like', '%' . $input['search'] . '%');
        }

        if (!empty($input['order_by'])) {
            $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }

        return $query
            ->paginate($per_page);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param array $input
     * @param $auth
     * @return array
     */
    final public function prepareData(array $input, $auth): array
    {
        $shop['description'] = $input['description'] ?? null;
        $shop['user_id'] = $auth->id();
        $shop['company'] = $input['company'] ?? null;
        $shop['email'] = $input['email'] ?? null;
        $shop['phone'] = $input['phone'] ?? null;
        $shop['status'] = $input['status'] ?? null;

        return $shop;
    }

    /**
     * @return Collection|array
     */
    final public function getShopsSelectList(): Collection|array
    {
        return self::query()
            ->where('status', self::STATUS_ACTIVE)
            ->select('id', 'company')
            ->get();
    }

    /**
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    final public function getShopDetailsById($id): Model|Collection|Builder|array|null
    {
        return self::query()
            ->with('address',
                'address.province:id,name_en,name_si',
                'address.district:id,name_en,name_si',
                'address.city:id,name_en,name_si')
            ->findOrFail($id);
    }
}
