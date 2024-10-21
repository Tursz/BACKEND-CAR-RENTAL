<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if (!$rental = Rental::count()) {
            return response()->json(['message' => 'No rental found'], Response::HTTP_OK);
        }

        return response()->json(['data' => $rental], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'payment_method' => ['required', 'string'],
            'start_date' => ['required', 'date']
        ]);
        $car = Car::find($request->car_id);
        $rental = Rental::create([
            'user_id' => $request->user()->id,
            'client_id' => $request->client_id,
            'car_id' => $request->car_id,
            'payment_method' => $request->payment_method,
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+1 month')),
            'is_in_installment' => $request->is_in_installment
        ]);
        if ($request->is_in_installment && $request->payment_method != 'PIX') {
            for ($i = 0; $i < $request->installment; $i++) {
                $payment = Payment::create([
                    'rental_id' => $rental->id,
                    'client_id' => $request->client_id,
                    'installment' => $i + 1,
                    'price' => $car->price / $request->installment,
                    'status' => 'unpaid',
                ]);
            }
            return response()->json(['data' => [$rental,$payment]], Response::HTTP_OK);
        }
        $payment = Payment::create([
            'rental_id' => $rental->id,
            'client_id' => $request->client_id,
            'installment' => 0,
            'price' => $car->price,
            'status' => 'paid',
        ]);
        return response()->json(['data' => [$rental,$payment]], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$rental = Rental::with('payments')->find($id)) {
            return response()->json(['data' => 'Rental not found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => $rental], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!$payment = Payment::find($id)){
            return response()->json(['message' => 'No payment found.'], Response::HTTP_NO_CONTENT);
        }

        $payment->update([
            'status' => $request->status,
        ]);
        return response()->json(['data' => $payment], Response::HTTP_OK);
    }

}
