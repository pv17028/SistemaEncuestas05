<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Privilegio;

class PrivilegioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $privilegios = Privilegio::all();
        return view('privilegio.index', compact('privilegios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('privilegio.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombrePrivilegio' => 'required|string|max:50',
            'descripcionPrivilegio' => 'nullable|string|max:256',
            'idRol' => 'required|exists:App\Models\Rol,idRol',
        ]);

        Privilegio::create($request->all());
        
        return redirect()->route('privilegio.index')->with('success', 'Privilegio creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Privilegio  $privilegio
     * @return \Illuminate\Http\Response
     */
    public function show(Privilegio $privilegio)
    {
        return view('privilegio.show', compact('privilegio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Privilegio  $privilegio
     * @return \Illuminate\Http\Response
     */
    public function edit(Privilegio $privilegio)
    {
        return view('privilegio.edit', compact('privilegio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Privilegio  $privilegio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Privilegio $privilegio)
    {
        $request->validate([
            'nombrePrivilegio' => 'required|string|max:50',
            'descripcionPrivilegio' => 'nullable|string|max:256',
            'idRol' => 'required|exists:App\Models\Rol,idRol',
        ]);

        $privilegio->update($request->all());

        return redirect()->route('privilegio.index')->with('success', 'Privilegio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Privilegio  $privilegio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Privilegio $privilegio)
    {
        $privilegio->delete();

        return redirect()->route('privilegio.index')->with('success', 'Privilegio eliminado correctamente.');
    }
}
