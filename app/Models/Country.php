<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];
    const STATUS_ACTIVE = TRUE;
    const STATUS_INACTIVE = FALSE;

    /**
     * @return Collection|array
     */
    final public function getCountryIdAndName(): Collection|array
    {
        return self::query()
            ->where('status', self::STATUS_ACTIVE)
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->get();
    }
}
