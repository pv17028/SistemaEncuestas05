<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

class EncuestaController extends Controller
{
    public function index()
    {
        $idUsuario = auth()->user()->id; // Obtener el ID del usuario autenticado
        $encuestas = Encuesta::where('idUsuario', $idUsuario)
            ->orderBy('titulo')
            ->get();

        foreach ($encuestas as $encuesta) {
            $encuesta->respuestas_agrupadas = $encuesta->respuestas()
                ->selectRaw("DATE_TRUNC('second', created_at) as fecha_hora")
                ->groupBy('fecha_hora')
                ->get()
                ->count();
        }

        return view('encuestas.index', compact('encuestas'));
    }

    // Mostrar el formulario para crear una nueva encuesta
    public function create()
    {
        return view('encuestas.create');
    }

    // Almacenar una nueva encuesta
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'objetivo' => 'required|string',
            'descripcionEncuesta' => 'required|string',
            'grupoMeta' => 'required|string|max:255',
            'fechaVencimiento' => 'required|date_format:Y-m-d\TH:i',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color_principal' => 'nullable|string|max:7',
            'color_secundario' => 'nullable|string|max:7',
        ]);

        // Crear la encuesta con el idUsuario del usuario autenticado
        $encuesta = new Encuesta;
        $encuesta->titulo = $request->titulo;
        $encuesta->objetivo = $request->objetivo;
        $encuesta->descripcionEncuesta = $request->descripcionEncuesta;
        $encuesta->grupoMeta = $request->grupoMeta;
        $encuesta->fechaVencimiento = $request->fechaVencimiento;
        $encuesta->idUsuario = $request->idUsuario; // Asignar el idUsuario del formulario

        // Subir el logo de la encuesta
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoNombre = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $logoNombre);
            $encuesta->logo = $logoNombre;
        }

        // Asignar los colores de la encuesta
        $encuesta->color_principal = $request->color_principal;
        $encuesta->color_secundario = $request->color_secundario;
        $encuesta->color_terciario = $request->color_terciario;
        $encuesta->color_cuarto = $request->color_cuarto;
        $encuesta->color_quinto = $request->color_quinto;

        $encuesta->save();

        // Redirige al usuario a la página de creación de preguntas con el id de la encuesta que acabas de crear
        return redirect()->route('preguntas.create', ['idEncuesta' => $encuesta->idEncuesta]);
    }

    public function show(Encuesta $encuesta)
    {
        // Verificar si la encuesta pertenece al usuario autenticado
        if ($encuesta->idUsuario != auth()->user()->id) {
            abort(403, 'No tienes permiso para ver esta encuesta.');
        }

        // Comprueba si la encuesta ha expirado
        $now = Carbon::now();
        $expirationDate = Carbon::parse($encuesta->fechaVencimiento);
        if ($now->greaterThan($expirationDate)) {
            $encuesta->compartida = false;
            $encuesta->compartida_con = null; // Borra los IDs de los usuarios a los que se les compartió la encuesta
            $encuesta->save();
        }

        $usuarios = User::all(); // Obtén todos los usuarios

        // Cargar la relación respuestasCount
        $encuesta->load('respuestasCount');

        return view('encuestas.show', compact('encuesta', 'usuarios')); // Pasa los usuarios a la vista
    }

    // Mostrar el formulario para editar una encuesta
    public function edit(Encuesta $encuesta)
    {
        // Verificar si la encuesta pertenece al usuario autenticado
        if ($encuesta->idUsuario != auth()->user()->id) {
            abort(403, 'No tienes permiso para editar esta encuesta.');
        }

        return view('encuestas.edit', compact('encuesta'));
    }

    // Actualizar una encuesta existente
    public function update(Request $request, Encuesta $encuesta)
    {
        // Validación de datos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'objetivo' => 'required|string',
            'descripcionEncuesta' => 'required|string',
            'grupoMeta' => 'required|string|max:255',
            'fechaVencimiento' => 'required|date_format:Y-m-d\TH:i',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color_principal' => 'nullable|string|max:7',
            'color_secundario' => 'nullable|string|max:7',
            'color_terciario' => 'nullable|string|max:7',
            'color_cuarto' => 'nullable|string|max:7',
            'color_quinto' => 'nullable|string|max:7',
        ]);

        // Actualizar la encuesta con los datos del formulario
        $encuesta->update([
            'titulo' => $request->titulo,
            'objetivo' => $request->objetivo,
            'descripcionEncuesta' => $request->descripcionEncuesta,
            'grupoMeta' => $request->grupoMeta,
            'fechaVencimiento' => $request->fechaVencimiento,
            'color_principal' => $request->color_principal,
            'color_secundario' => $request->color_secundario,
            'color_terciario' => $request->color_terciario,
            'color_cuarto' => $request->color_cuarto,
            'color_quinto' => $request->color_quinto,
        ]);

        // Subir el logo de la encuesta
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoNombre = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $logoNombre);
            $encuesta->logo = $logoNombre;
            $encuesta->save();
        }

        // Redirigir al usuario a los detalles de la encuesta
        return redirect()->route('encuestas.show', ['encuesta' => $encuesta->idEncuesta])
            ->with('success', 'Encuesta actualizada correctamente.');
    }

    // Eliminar una encuesta existente
    public function destroy(Encuesta $encuesta)
    {
        $encuesta->delete();
        return redirect()->route('encuestas.index')->with('success', 'Encuesta eliminada exitosamente.');
    }

    public function compartir(Request $request, $idEncuesta)
    {
        $encuesta = Encuesta::findOrFail($idEncuesta);

        $compartirCon = $request->input('shareOptions');
        $usuariosSeleccionados = $request->input('users');
        $fechaVencimiento = Carbon::parse($encuesta->fechaVencimiento);

        if ($fechaVencimiento->isPast()) {
            // La fecha de vencimiento ha pasado, redirige con un mensaje de error
            return redirect()->route('encuestas.show', $encuesta)->with('error', 'La encuesta ya venció.');
        }

        if ($compartirCon == 'todos') {
            // Si la encuesta se comparte con todos los usuarios, envía un correo electrónico a todos los usuarios
            $usuarios = User::all();
            $encuesta->compartida = true;
            $encuesta->compartida_con = $usuarios->pluck('id')->implode(','); // Almacena los IDs de los usuarios
        } elseif ($compartirCon == 'algunos' && $usuariosSeleccionados) {
            // Si la encuesta se comparte con algunos usuarios, envía un correo electrónico solo a los usuarios seleccionados
            $usuarios = User::whereIn('id', $usuariosSeleccionados)->get();
            $encuesta->compartida = true;
            $encuesta->compartida_con = implode(',', $usuariosSeleccionados); // Almacena los IDs de los usuarios
        } else {
            // Si no se seleccionó ninguna opción o no se seleccionó ningún usuario, redirige con un mensaje de error
            return redirect()->route('encuestas.show', $encuesta)->with('error', 'Debes seleccionar una opción de compartir y al menos un usuario.');
        }
        $encuesta->save();

        foreach ($usuarios as $usuario) {
            Mail::send('emails.share', ['encuesta' => $encuesta], function ($message) use ($usuario) {
                $message->to($usuario->correoElectronico)
                    ->subject('Se ha compartido una encuesta contigo');
            });
        }

        return redirect()->route('encuestas.show', $encuesta)->with('success', 'Encuesta compartida correctamente.');
    }

    public function unshare($idEncuesta)
    {
        $encuesta = Encuesta::findOrFail($idEncuesta);
        $encuesta->compartida = false;
        $encuesta->compartida_con = null; // Borra los IDs de los usuarios a los que se les compartió la encuesta
        $encuesta->save();
        return redirect()->route('encuestas.show', $encuesta)->with('success', 'Encuesta ya no está compartida.');
    }
}
