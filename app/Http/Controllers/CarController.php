<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Services\FilterService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{

    public $car;
    public function __construct(Car $car){
        $this->car = $car;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => 'like',
            'brand_id' => '=',
            'color_id' => '=',
            'is_active' => '=',
            'type_id' => '=',
            'price' => '>=',
            'year' => '=',
        ];

        $car = FilterService::filterCar($this->car,$filter,$request);

        return response()->json(['data' => $car[0]], $car[1]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'color_id' => ['required', 'integer', 'exists:colors,id'],
            'name' => ['required', 'string', 'max:255'],
            'plate' => ['required', 'string', 'max:7', 'unique:cars'],
            'km' => ['required', 'numeric'],
            'chassi' => ['required', 'string', 'max:255'],
            'is_available' => ['in:available,not_available'],
            'year' => ['required', 'date'],
            'price' => ['required', 'decimal:2'],
        ]);

        $car = Car::create($request->all());
        return response()->json(['data' => $car], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$car = Car::with('brand', 'type', 'color')->find($id)) {
            return response()->json(['data' => 'Car not found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => $car], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!$car = Car::find($id)) {
            return response()->json(['data' => 'Car not found'], Response::HTTP_NO_CONTENT);
        }
        $request->validate([
            'brand_id' => ['integer', 'exists:brands,id'],
            'type_id' => ['integer', 'exists:types,id'],
            'color_id' => ['integer', 'exists:colors,id'],
            'year' => ['date'],
            'price' => ['decimal:2'],
        ]);

        $car->update([
            'brand_id' => $request->brand_id ?: $car->brand_id,
            'type_id' => $request->type_id ?: $car->type_id,
            'color_id' => $request->color_id ?: $car->color_id,
            'name' => $request->name ?: $car->name,
            'plate' => $request->plate ?: $car->plate,
            'km' => $request->km ?: $car->km,
            'chassi' => $request->chassi ?: $car->chassi,
            'is_available' => $request->is_available ?: $car->is_available,
            'year' => $request->year ?: $car->year,
            'price' => $request->price ?: $car->price,
        ]);

        return response()->json(['data' => $car], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($car = Car::find($id)) {
            return response()->json(['data' => 'No cars found'], Response::HTTP_NO_CONTENT);
        }

        if (!Rental::where('car_id', $id)->first()) {
            $car->delete();
            return response()->json(['data' => 'Car deleted successfully'], Response::HTTP_OK);
        }

        $car->forceDelete();

        return response()->json(['data' => 'Car deleted successfully'], Response::HTTP_OK);
    }
}
