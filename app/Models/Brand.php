<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class Brand extends Model
{
    use HasFactory;

    public const LOGO_UPLOAD_PATH = 'images/uploads/brand/';
    public const THUMB_LOGO_UPLOAD_PATH = 'images/uploads/brand_thumb/';
    const STATUS_ACTIVE = TRUE;
    const STATUS_INACTIVE = FALSE;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'status',
    ];

    /**
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    final public function storeBrand(array $input): Model|Builder
    {
        return self::query()->create($input);
    }

    /**
     * @param array $input
     * @return LengthAwarePaginator
     */
    final public function getAllBrands(array $input): LengthAwarePaginator
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
     * @return Collection|array
     */
    final public function getBrandIdAndName(): Collection|array
    {
        return self::query()
            ->where('status', self::STATUS_ACTIVE)
            ->select('id', 'name')
            ->get();
    }
}
