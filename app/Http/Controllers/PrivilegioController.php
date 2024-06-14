<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Privilegio;
use Illuminate\Support\Facades\DB;

class PrivilegioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $privilegios = Privilegio::orderBy('idPrivilegio')->get();
        return view('privilegios.index', compact('privilegios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('privilegios.create');
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
            'url' => 'required|string|max:256',
        ]);
    
        Privilegio::create($request->all());
    
        return redirect()->route('privilegios.index')->with('success', 'Privilegio creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Privilegio  $privilegio
     * @return \Illuminate\Http\Response
     */
    public function show(Privilegio $privilegio)
    {
        return view('privilegios.show', compact('privilegio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Privilegio  $privilegio
     * @return \Illuminate\Http\Response
     */
    public function edit(Privilegio $privilegio)
    {
        return view('privilegios.edit', compact('privilegio'));
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
            'url' => 'required|string|max:256',
        ]);
    
        $privilegio->update($request->all());
    
        return redirect()->route('privilegios.index')->with('success', 'Privilegio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Privilegio  $privilegio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Privilegio $privilegio)
    {
        // Elimina todas las referencias al privilegio en la tabla rol_privilegio
        DB::table('rol_privilegio')->where('idPrivilegio', $privilegio->idPrivilegio)->delete();
    
        // Ahora puedes eliminar el privilegio
        $privilegio->delete();
    
        return redirect()->route('privilegios.index')->with('success', 'Privilegio eliminado correctamente.');
    }
}
