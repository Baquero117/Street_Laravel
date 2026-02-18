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
        if (!Session::has('token')) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para ver tu carrito');
        }

        $carrito = $this->carritoService->obtenerCarrito();

        return view('CarritoCompras.Carrito', compact('carrito'));
    }

    // ✅ MEJORADO: Agregar producto con manejo de errores de stock
    public function agregar(Request $request) {
        Log::info('Datos recibidos en Laravel:', $request->all());

        if (!session()->has('token')) {
            return response()->json(['success' => false, 'mensaje' => 'No hay sesión'], 401);
        }

        $resultado = $this->carritoService->agregarProducto($request->all());
        
        // ✅ Manejar códigos de error de stock
        if (isset($resultado['resultado'])) {
            if ($resultado['resultado'] == -1) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Stock insuficiente para agregar este producto',
                    'tipo_error' => 'stock_insuficiente'
                ], 400);
            } elseif ($resultado['resultado'] == -2) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La cantidad total supera el stock disponible',
                    'tipo_error' => 'supera_stock'
                ], 400);
            }
        }
        
        return response()->json($resultado);
    }

    // ✅ MEJORADO: Eliminar item del carrito
    public function eliminar($idDetalleCarrito)
    {
        if (!Session::has('token')) {
            return response()->json(['success' => false, 'mensaje' => 'No autenticado'], 401);
        }

        $resultado = $this->carritoService->eliminarItem($idDetalleCarrito);

        return response()->json($resultado);
    }

    // ✅ MEJORADO: Actualizar cantidad con validación de stock
    public function actualizar(Request $request)
    {
        if (!Session::has('token')) {
            return response()->json(['success' => false, 'mensaje' => 'No autenticado'], 401);
        }

        $resultado = $this->carritoService->actualizarCantidad(
            $request->id_carrito,
            $request->cantidad
        );

        // ✅ Manejar error de stock insuficiente
        if (isset($resultado['resultado']) && $resultado['resultado'] == -1) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Stock insuficiente. No se puede actualizar la cantidad.',
                'tipo_error' => 'stock_insuficiente'
            ], 400);
        }

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

    // ✅ MEJORADO: Checkout con validación de stock antes de proceder
    public function checkout()
    {
        if (!session()->has('token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar');
        }

        $carrito = $this->carritoService->obtenerCarrito();

        if (!isset($carrito['items']) || empty($carrito['items'])) {
            return redirect()->route('carrito')->with('warning', 'Tu carrito está vacío. Agrega productos antes de continuar.');
        }

        // ✅ NUEVO: Validar stock de todos los items antes de proceder
        $erroresStock = [];
        foreach ($carrito['items'] as $item) {
            $stockDisponible = $item['stock_disponible'] ?? 0;
            $cantidadCarrito = $item['cantidad'];
            
            if ($stockDisponible < $cantidadCarrito) {
                $erroresStock[] = "{$item['nombre']} (Talla {$item['talla']}): Solo quedan {$stockDisponible} unidades disponibles";
            }
        }

        if (!empty($erroresStock)) {
            $mensajeError = "Algunos productos no tienen suficiente stock:\n" . implode("\n", $erroresStock);
            return redirect()->route('carrito')->with('error', $mensajeError);
        }

        $perfil = $this->perfilService->obtenerPerfil();

        if (!$perfil) {
            return redirect()->route('login')->with('error', 'No se pudo obtener tu información. Por favor inicia sesión nuevamente.');
        }

        $usuario = (object)[
            'id' => $perfil['id_cliente'] ?? session('usuario_id'),
            'nombre' => $perfil['nombre'] ?? session('usuario_nombre'),
            'apellido' => $perfil['apellido'] ?? '',
            'email' => $perfil['correo_electronico'] ?? session('usuario_correo'),
            'telefono' => $perfil['telefono'] ?? '',
            'direccion' => $perfil['direccion'] ?? '',
            'tipo' => session('usuario_tipo')
        ];

        return view('CarritoCompras.Pedido', compact('carrito', 'usuario'));
    }
}