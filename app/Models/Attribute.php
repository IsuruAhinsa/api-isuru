<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = TRUE;
    const STATUS_INACTIVE = FALSE;

    protected $fillable = [
        'user_id', 'name', 'status'
    ];

    /**
     * @param array $input
     * @return LengthAwarePaginator
     */
    final public function getProductAttributes(array $input): LengthAwarePaginator
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
     * @return HasMany
     */
    public function values(): HasMany
    {
        return $this->hasMany(Value::class);
    }

    /**
     * @return Collection|array
     */
    final public function getAttributeIdAndNameWithValues(): Collection|array
    {
        return self::query()
            ->select('id', 'name')
            ->with('values:id,name,attribute_id')
            ->where('status', self::STATUS_ACTIVE)
            ->get();
    }
}
