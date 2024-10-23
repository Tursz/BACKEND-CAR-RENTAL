<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Brand::count()) {
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
            'logo' => ['required', 'image']
        ]);

        //Recebe o url do host
        $url = $request->url();
        //Formata a url para mostrar a imagem pelo url
        $url = Str::before($url, 'api') . 'storage/';
        $brand = Brand::create([
            'name' => $request->name,
            //Concatena a url com o arquivo da imagem para armazenar no banco
            'logo' => $url . $request->logo->store('brands', 'public')
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
            'name' => ['string', 'max:100', 'min:3'],
            'logo' => ['image']
        ]);
        if (!$brand = Brand::find($id)) {
            return response()->json(['data' => 'Brand not found'], Response::HTTP_NO_CONTENT);
        }
        if ($request->logo) {
            //Formata a url fornecida pelo banco para o laravel conseguir encontrar o caminho da imagem
            $logo = Str::after($brand->logo, 'storage/');
            //Formata a url para adicionar a nova imagem no banco
            $url = $request->url();
            $url = Str::before($url, 'api') . 'storage/';
            //Deleta a imagem anterior do storage
            Storage::disk('public')->delete($logo);

            $brand->update([
                'name' => $request->name ?: $brand->name,
                //Concatena a url com o arquivo da imagem para armazenar no banco
                'logo' => $url . $request->logo->store('brands', 'public')
            ]);

            return response()->json(['data' => $brand], Response::HTTP_OK);
        }

        $brand->update([
            'name' => $request->name ?: $brand->name,
        ]);

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

        if (!Car::where('brand_id', $id)->count()) {
            $brand->delete();
            return response()->json(['message' => 'Brand deleted'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['message' => 'Cannot delete brand with associated cars'], Response::HTTP_UNAUTHORIZED);
    }
}
