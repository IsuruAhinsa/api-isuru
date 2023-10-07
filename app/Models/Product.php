<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'category_id',
        'country_id',
        'supplier_id',
        'sub_category_id',
        'created_by_id',
        'updated_by_id',
        'name',
        'slug',
        'status',
        'cost',
        'price',
        'stock',
        'discount',
        'discount_fixed',
        'discount_start',
        'discount_end',
        'sku',
        'description',
    ];

    /**
     * @param array $input
     * @return Builder|Model
     */
    final public function storeProduct(array $input): Builder|Model
    {
        return self::query()->create($this->prepareData($input));
    }

    /**
     * @param array $input
     * @return array
     */
    final public function prepareData(array $input): array
    {
        return [
            'brand_id' => $input['brand'] ?? 0,
            'category_id' => $input['category'] ?? 0,
            'country_id' => $input['country'] ?? 0,
            'supplier_id' => $input['supplier'] ?? 0,
            'sub_category_id' => $input['subCategory'] ?? 0,
            'created_by_id' => auth()->id(),
            'updated_by_id' => auth()->id(),
            'name' => $input['name'] ?? null,
            'slug' => $input['slug'] ? Str::slug($input['slug']) : null,
            'status' => $input['status'] ?? null,
            'cost' => $input['cost'] ?? 0,
            'price' => $input['cost'] ?? null,
            'stock' => $input['stock'] ?? null,
            'discount' => $input['discount'] ?? 0,
            'discount_fixed' => $input['discount_fixed'] ?? 0,
            'discount_start' => $input['discount_start'] ?? null,
            'discount_end' => $input['discount_end'] ?? null,
            'sku' => $input['sku'] ?? null,
            'description' => $input['description'] ?? null,
        ];
    }

    /**
     * @param array $input
     * @return LengthAwarePaginator
     */
    final public function getAllProducts(array $input): LengthAwarePaginator
    {
        $query = self::query()->with([
            'category:id,name',
            'sub_category:id,name',
            'brand:id,name',
            'country:id,name',
            'supplier:id,company,phone',
            'created_by:id,name',
            'updated_by:id,name',
            'primary_photo',
            'product_attributes',
            'product_attributes.attributes',
            'product_attributes.attribute_value',
        ]);

        $per_page = $input['per_page'] ?? 5;

        if (!empty($input['search'])) {
            $query->where('name', 'like', '%' . $input['search'] . '%')
                ->orWhere('price', 'like', '%' . $input['search'] . '%')
                ->orWhere('sku', 'like', '%' . $input['search'] . '%');
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
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return BelongsTo
     */
    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * @return BelongsTo
     */
    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    /**
     * @return HasOne
     */
    public function primary_photo(): HasOne
    {
        return $this->hasOne(ProductPhoto::class)
            ->where('is_primary', 1);
    }

    /**
     * @return HasMany
     */
    public function product_attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
