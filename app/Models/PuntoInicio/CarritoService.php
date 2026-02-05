<?php

namespace App\Models\PuntoInicio;

class CarritoService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = "http://localhost:8080/carrito";
    }

    // Obtener carrito según el cliente autenticado
    public function obtenerCarrito($idCliente)
    {
        $curl = curl_init("{$this->baseUrl}/{$idCliente}");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $respuesta = curl_exec($curl);
        $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $http === 200 ? json_decode($respuesta, true) : [];
    }

    // (Opcional) Agregar producto
    public function agregarItem($datos)
    {
        $curl = curl_init($this->baseUrl);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datos));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json"
        ]);

        $respuesta = curl_exec($curl);
        $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $http === 201;
    }

    // (Opcional) Eliminar item
    public function eliminarItem($idItem)
    {
        $curl = curl_init("{$this->baseUrl}/{$idItem}");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

        curl_exec($curl);
        $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $http === 200;
    }
}
