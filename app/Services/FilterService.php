<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Car;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterService
{
    public static function filterBrand(Brand $brand, $filter, Request $request)
    {
        $query = $brand;

        //Procura e aplica os filtros requisitados
        foreach ($filter as $key => $operator) {
            $query->when($request->has($key), function ($q) use ($key, $operator, $request) {
                $value = $operator === 'like' ? '%' . $request->$key . '%' : $request->$key;
                return $q->where($key, $operator, $value);
            });
        }

        //Validação se existe dados com a query aplicada
        if(!$query->exists()){
            return ['No data Found',Response::HTTP_NO_CONTENT];
        }

        //Retorna e carrega as relação com paginação
        return [
            $query->with(['cars:id,brand_id,name,color_id as carros'])->withCount('cars')->paginate(10),
            Response::HTTP_OK
        ];
    }

    public static function filterCar(Car $car, $filter, Request $request)
    {
        $query = $car;

        //Procura e aplica os filtros requisitados
        foreach ($filter as $key => $operator) {
            $query->when($request->has($key), function ($q) use ($key, $operator, $request) {
                $value = $operator === 'like' ? '%' . $request->$key . '%' : $request->$key;
                return $q->where($key, $operator, $value);
            });
        }

        //Validação se existe dados com a query aplicada
        if($query->exists()){
            return ['No data Found',Response::HTTP_NO_CONTENT];
        }

        //Retorna e carrega as relação com paginação
        return [$query->with(['brand:id,name,logo'])->paginate(10), Response::HTTP_OK];
    }
}
