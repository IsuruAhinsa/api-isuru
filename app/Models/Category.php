<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Category extends Model
{
    use HasFactory;

    public const IMAGE_UPLOAD_PATH = 'images/uploads/category/';
    public const THUMB_IMAGE_UPLOAD_PATH = 'images/uploads/category_thumb/';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'serial',
        'description',
        'photo',
        'status',
    ];

    /**
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    final public function storeCategory(array $input): Model|Builder
    {
        return self::query()->create($input);
    }
    public function updateCategory(array $input)
    {
        return self::query()->where('id', $input['id'])->update($input);
    }

    /**
     * @param array $input
     * @return LengthAwarePaginator
     */
    final public function getAllCategories(array $input): LengthAwarePaginator
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
     * @return Collection
     */
    final public function getCategoryIdAndName(): Collection
    {
        return self::query()->select('name', 'id')->get();
    }
}
