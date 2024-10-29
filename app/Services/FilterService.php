<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Http\Request;

class FilterService
{
    public static function filterBrand(Brand $brand, $filter, Request $request)
    {
        $query = $brand->query();

        foreach ($filter as $key => $operator) {
            $query->when($request->has($key), function ($q) use ($key, $operator, $request) {
                $value = $operator === 'like' ? '%' . $request->$key . '%' : $request->$key;
                return $q->where($key, $operator, $value);
            });
        }
        return $query->paginate(5);
    }
}
