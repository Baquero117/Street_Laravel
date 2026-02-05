<?php

namespace App\Http\Controllers\Administrador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrador\ClienteService;
use Illuminate\Support\Facades\Session;

class ClienteController extends Controller
{
    private $clienteService;

    public function __construct(ClienteService $clienteService)
    {
       
        if (!session()->has('token')) {
            redirect()->route('login')->send();
        }

        $this->clienteService = $clienteService;
    }

    public function index()
    {
        $clientes = $this->clienteService->obtenerClientes();
        $mensaje = Session::get('mensaje', '');

        return view('Administrador.Cliente', compact('clientes', 'mensaje'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'contrasena' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:255',
        ]);

        $resultado = $this->clienteService->agregarCliente(
            $request->nombre,
            $request->apellido,
            $request->contrasena,
            $request->direccion,
            $request->telefono,
            $request->correo_electronico
        );

        Session::flash('mensaje', $resultado['success']
            ? "Cliente agregado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('admin.Cliente');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|numeric',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'contrasena' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'correo_electronico' => 'required|email|max:255',
        ]);

        $id = $request->id_cliente;

        $resultado = $this->clienteService->actualizarCliente(
            $id,
            $request->nombre,
            $request->apellido,
            $request->contrasena, // Siempre viene el placeholder
            $request->direccion,
            $request->telefono,
            $request->correo_electronico
        );

        Session::flash('mensaje', $resultado['success']
            ? "Cliente actualizado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('admin.Cliente');
    }

    public function destroy(Request $request)
    {
        $id = $request->id_cliente;

        $resultado = $this->clienteService->eliminarCliente($id);

        Session::flash('mensaje', $resultado['success']
            ? "Cliente eliminado correctamente."
            : "Error: " . $resultado['error']
        );

        return redirect()->route('admin.Cliente');
    }
}