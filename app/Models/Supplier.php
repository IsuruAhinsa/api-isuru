<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Supplier extends Model
{
    use HasFactory;

    public const LOGO_UPLOAD_PATH = 'images/uploads/supplier/';
    public const THUMB_LOGO_UPLOAD_PATH = 'images/uploads/supplier_thumb/';

    protected $fillable = [
        'user_id',
        'province_id',
        'district_id',
        'city_id',
        'company',
        'email',
        'phone',
        'description',
        'logo',
        'status',
        'address',
        'landmark',
    ];

    /**
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    final public function storeSupplier(array $input): Model|Builder
    {
        return self::query()->create($input);
    }

    /**
     * @param array $input
     * @return LengthAwarePaginator
     */
    final public function getAllSuppliers(array $input): LengthAwarePaginator
    {
        $query = self::query();

        $per_page = $input['per_page'] ?? 5;

        if (!empty($input['search'])) {
            $query->where('name', 'like', '%'.$input['search'].'%');
        }

        if (!empty($input['order_by'])) {
            $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }

        return $query
            ->with('user:id,name')
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
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * @return BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
