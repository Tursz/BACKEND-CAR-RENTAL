<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Brand::first()) {
            return response()->json(['data' => 'No brands'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => Brand::with('cars')->get()], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'image' => ['required', 'image']
        ]);

        $brand = Brand::create([
            'name' => $request->name,
            'image' => $request->image->store('brands', 'public')
        ]);

        return response()->json(['data' => $brand], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$brand = Brand::find($id)) {
            return response()->json(['data' => 'Brand not found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => $brand], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'image' => ['image']
        ]);
        if (!$brand = Brand::find($id)) {
            return response()->json(['data' => 'Brand not found'], Response::HTTP_NO_CONTENT);
        }

        $brand->update($request->all());

        return response()->json(['data' => $brand], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$brand = Brand::find($id)) {
            return response()->json(['data' => 'Brand not found'], Response::HTTP_NO_CONTENT);
        }

        if (!Car::where('brand_id', $id)->first()) {
            $brand->delete();
            return response()->json(['message' => 'Brand deleted'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['message' => 'Cannot delete brand with associated cars'], Response::HTTP_UNAUTHORIZED);
    }
}
