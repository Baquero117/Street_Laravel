<?php

namespace App\Http\Controllers\Carrito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Carrito\CarritoService;
use App\Models\PuntoInicio\PerfilService;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    private $carritoService;
    private $perfilService;

    public function __construct(CarritoService $carritoService, PerfilService $perfilService)
    {
        $this->carritoService = $carritoService;
        $this->perfilService = $perfilService;
    }

    // 🔒 Mostrar vista del carrito (requiere autenticación)
    public function index()
    {
        // Verificar si el usuario inició sesión
        if (!Session::has('token')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para ver tu carrito');
        }

        // Obtener el carrito del usuario autenticado
        $carrito = $this->carritoService->obtenerCarrito();

        return view('CarritoCompras.Carrito', compact('carrito'));
    }

    public function agregar(Request $request) {
    // Log para depuración
    Log::info('Datos recibidos en Laravel:', $request->all());

    if (!session()->has('token')) {
        return response()->json(['success' => false, 'mensaje' => 'No hay sesión'], 401);
    }

    $resultado = $this->carritoService->agregarProducto($request->all());
    
    // Si Java responde con éxito pero no devuelve success:true explícito
    return response()->json($resultado);
}

    // Eliminar item del carrito
    public function eliminar($idDetalleCarrito)
    {
        if (!Session::has('token')) {
            return response()->json(['success' => false, 'mensaje' => 'No autenticado'], 401);
        }

        $resultado = $this->carritoService->eliminarItem($idDetalleCarrito);

        return response()->json($resultado);
    }

    // Actualizar cantidad
    public function actualizar(Request $request)
    {
        if (!Session::has('token')) {
            return response()->json(['success' => false, 'mensaje' => 'No autenticado'], 401);
        }

        $resultado = $this->carritoService->actualizarCantidad(
            $request->id_carrito, // Ahora es id_detalle_carrito
            $request->cantidad
        );

        return response()->json($resultado);
    }

    // Obtener contador
    public function contador()
    {
        if (!Session::has('token')) {
            return response()->json(['cantidad' => 0]);
        }

        $resultado = $this->carritoService->obtenerContador();

        return response()->json($resultado);
    }

    // Vaciar carrito
    public function vaciar()
    {
        if (!Session::has('token')) {
            return response()->json(['success' => false, 'mensaje' => 'No autenticado'], 401);
        }

        $resultado = $this->carritoService->vaciarCarrito();

        return response()->json($resultado);
    }

        public function checkout()
    {
        // Verificar que el usuario tenga sesión activa (token de Spring Boot)
        if (!session()->has('token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar');
        }

        // Obtener carrito usando el servicio
        $carrito = $this->carritoService->obtenerCarrito();

        // Verificar que el carrito tenga items
        if (!isset($carrito['items']) || empty($carrito['items'])) {
            return redirect()->route('carrito')->with('warning', 'Tu carrito está vacío. Agrega productos antes de continuar.');
        }

        // 👇 OBTENER PERFIL COMPLETO DEL USUARIO
        $perfil = $this->perfilService->obtenerPerfil();

        if (!$perfil) {
            return redirect()->route('login')->with('error', 'No se pudo obtener tu información. Por favor inicia sesión nuevamente.');
        }

        // Crear objeto de usuario con los datos del perfil
        $usuario = (object)[
            'id' => $perfil['id_cliente'] ?? session('usuario_id'),
            'nombre' => $perfil['nombre'] ?? session('usuario_nombre'),
            'apellido' => $perfil['apellido'] ?? '',
            'email' => $perfil['correo_electronico'] ?? session('usuario_correo'),
            'telefono' => $perfil['telefono'] ?? '',
            'direccion' => $perfil['direccion'] ?? '',
            'tipo' => session('usuario_tipo')
        ];

        // Retornar la vista de checkout
        return view('CarritoCompras.Pedido', compact('carrito', 'usuario'));
    }
}