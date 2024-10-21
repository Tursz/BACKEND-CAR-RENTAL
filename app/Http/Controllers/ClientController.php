<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Client::count()) {
            return response()->json(['data' => 'No clients'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => Client::with('rentals')->get()], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120', 'min:5'],
            'email' => ['required', 'email', 'unique'],
            'cpf' => ['required', 'min:11', 'unique'],
            'cnpj' => ['required', 'min:14', 'unique'],
            'cnh' => ['required', 'min:11', 'unique'],
            'phone_number' => ['required', 'unique'],
            'address' => ['required'],
            'birth_date' => ['required'],
        ]);

        $client = Client::create($request->all());
        return response()->json(['data' => $client], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$client = Client::find($id)) {
            return response()->json(['data' => 'Client not found'], Response::HTTP_NO_CONTENT);
        }

        return response()->json(['data' => $client], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::find($id);
        $request->validate([
            'name' => ['required', 'string', 'max:120', 'min:5'],
            'email' => ['required', 'email', Rule::unique('clients')->ignore($client->id)],
            'cpf' => ['required', 'min:11', Rule::unique('clients')->ignore($client->id)],
            'cnpj' => ['required', 'min:14', Rule::unique('clients')->ignore($client->id)],
            'cnh' => ['required', 'min:11', Rule::unique('clients')->ignore($client->id)],
            'phone_number' => ['required', Rule::unique('clients')->ignore($client->id)],
            'address' => ['required'],
            'birth_date' => ['required'],
        ]);

        if (!$client) {
            return response()->json(['data' => 'Client not found'], Response::HTTP_NO_CONTENT);
        }

        $client->update([
            'name' => $request->name ?: $client->name,
            'email' => $request->email ?: $client->email,
            'cpf' => $request->cpf ?: $client->cpf,
            'cnpj' => $request->cnpj ?: $client->cnpj,
            'cnh' => $request->cnh ?: $client->cnh,
            'phone_number' => $request->phone_number ?: $client->phone_number,
            'address' => $request->address ?: $client->address,
            'birth_date' => $request->birth_date ?: $client->birth_date,
        ]);

        return response()->json(['data' => $client], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if (!$client = Client::find($id)) {
            return response()->json(['data' => 'Client not found'], Response::HTTP_NO_CONTENT);
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted'], Response::HTTP_NO_CONTENT);
    }
}
