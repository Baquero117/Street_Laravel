<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\PromocionService;
use Illuminate\Support\Facades\Session;

class PromocionController extends Controller
{
    private $promocionService;

    public function __construct(PromocionService $promocionService)
    {
        
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->promocionService = $promocionService;
    }

    public function index()
    {
        $promociones = $this->promocionService->obtenerPromociones();
        $mensaje = Session::get('mensaje', '');

        return view('Administrador.Promocion', compact('promociones', 'mensaje'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'descuento'   => 'required|numeric',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'id_producto'  => 'required|numeric'
        ]);

        $resultado = $this->promocionService->crearPromocion(
            $request->descripcion,
            $request->descuento,
            $request->fecha_inicio,
            $request->fecha_fin,
            $request->id_producto
        );

        Session::flash('mensaje', $resultado['success']
            ? "Promoción agregada correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('promocion.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_promocion' => 'required|numeric',
            'descripcion'  => 'required|string|max:255',
            'descuento'    => 'required|numeric',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'id_producto'  => 'required|numeric'
        ]);

        $resultado = $this->promocionService->actualizarPromocion(
            $request->id_promocion,
            $request->descripcion,
            $request->descuento,
            $request->fecha_inicio,
            $request->fecha_fin,
            $request->id_producto
        );

        Session::flash('mensaje', $resultado['success']
            ? "Promoción actualizada correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('promocion.index');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id_promocion' => 'required|numeric'
        ]);

        $resultado = $this->promocionService->eliminarPromocion($request->id_promocion);

        Session::flash('mensaje', $resultado['success']
            ? "Promoción eliminada correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('promocion.index');
    }
}
