<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Type;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Type::first()) {
            return response()->json(['data' => 'No type found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => Type::all()], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120', 'min:5'],
        ]);

        $type = Type::create($request->all());

        return response()->json(['data' => $type], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$type = Type::find($id)) {
            return response()->json(['data' => 'Type not found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => $type], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:120']
        ]);

        if (!$type = Type::find($id)) {
            return response()->json(['data' => 'Type not found'], Response::HTTP_NO_CONTENT);
        }

        $type->update($request->all());

        return response()->json(['data' => $type], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$type = Type::find($id)) {
            return response()->json(['data' => 'Type not found'], Response::HTTP_NO_CONTENT);
        }

        if (!Car::where('type_id', $id)) {
            $type->delete();
            return response()->json(['data' => 'Type deleted'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => 'Cannot delete type with associated cars'], Response::HTTP_UNAUTHORIZED);
    }
}
