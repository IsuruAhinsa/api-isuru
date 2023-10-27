<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone'];

    /**
     * @param $search
     * @return Collection|array
     */
    final public function getCustomerBySearch($search): Collection|array
    {
        return self::query()
            ->select('id', 'name', 'phone')
            ->where('name', 'like', '%'.$search.'%')
            ->Orwhere('phone', 'like', '%'.$search.'%')
            ->take(10)
            ->get();
    }
}
