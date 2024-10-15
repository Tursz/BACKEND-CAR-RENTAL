<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Color;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Color::first()) {
            return response()->json(['data' => 'No colors'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => Color::all()], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120', 'min:5'],
            'hex_code' => ['required', 'regex:/#[0-9A-Fa-f]{6}/'],
        ]);

        $color = Color::create($request->all());
        return response()->json(['data' => $color], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$color = Color::find($id)) {
            return response()->json(['data' => 'Color not found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => $color], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120', 'min:5'],
            'hex_code' => ['required', 'regex:/#[0-9A-Fa-f]{6}/'],
        ]);
        if (!$color = Color::find($id)) {
            return response()->json(['data' => 'Color not found'], Response::HTTP_NO_CONTENT);
        }

        $color->update([
            'name' => $request->name ?: $color->name,
            'hex_code' => $request->hex_code ?: $color->hex_code,
        ]);
        return response()->json(['data' => $color], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$color = Color::find($id)) {
            return response()->json(['data' => 'Color not found'], Response::HTTP_NO_CONTENT);
        }

        if (!Car::where('color_id', $id)->first()) {
            $color->delete();
            return response()->json(['message' => 'Color deleted'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['message' => 'Cannot delete color with associated cars'], Response::HTTP_UNAUTHORIZED);
    }
}
