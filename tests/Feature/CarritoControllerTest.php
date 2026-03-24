<?php

namespace Tests\Feature;

use Tests\TestCase;

class CarritoControllerTest extends TestCase
{
    // index - Retorna vista con token válido
    public function test_index_retorna_vista_carrito_autenticado()
    {
        $response = $this->withSession(['token' => 'token_valido'])
            ->get('/carrito');
        
        $this->assertTrue($response->headers->has('Cache-Control'));
    }
    // php artisan test --filter=test_index_retorna_vista_carrito_autenticado

    public function test_index_redirige_sin_token()
    {
        $response = $this->get('/carrito');
        $this->assertEquals(302, $response->getStatusCode());
    }
    // php artisan test --filter=test_index_redirige_sin_token

    public function test_index_tiene_headers_cache()
    {
        $response = $this->withSession(['token' => 'token_valido'])
            ->get('/carrito');
        $this->assertTrue($response->headers->has('Cache-Control'));
        $this->assertTrue($response->headers->has('Pragma'));
        $this->assertTrue($response->headers->has('Expires'));
    }
    // php artisan test --filter=test_index_tiene_headers_cache

    public function test_agregar_producto_sin_sesion()
    {
        $response = $this->post('/carrito/agregar', ['id_detalle_producto' => 5, 'talla' => 'M', 'cantidad' => 2, 'precio' => 99.99]);
        $this->assertEquals(302, $response->getStatusCode());
    }
    // php artisan test --filter=test_agregar_producto_sin_sesion

    public function test_eliminar_item_sin_autenticacion()
    {
        $response = $this->delete('/carrito/eliminar/1');
        $this->assertEquals(302, $response->getStatusCode());
    }
    // php artisan test --filter=test_eliminar_item_sin_autenticacion

    public function test_contador_con_autenticacion()
    {
        $response = $this->withSession(['token' => 'token_valido'])
            ->get('/carrito/contador');
        $this->assertEquals(200, $response->getStatusCode());
    }
    // php artisan test --filter=test_contador_con_autenticacion

    public function test_contador_sin_autenticacion()
    {
        $response = $this->get('/carrito/contador');
        $this->assertEquals(302, $response->getStatusCode());
    }
    // php artisan test --filter=test_contador_sin_autenticacion

    public function test_vaciar_carrito_sin_autenticacion()
    {
        $response = $this->delete('/carrito/vaciar');
        $this->assertEquals(302, $response->getStatusCode());
    }
    // php artisan test --filter=test_vaciar_carrito_sin_autenticacion

    public function test_checkout_sin_sesion()
    {
        $response = $this->get('/checkout');
        $this->assertEquals(302, $response->getStatusCode());
    }
    // php artisan test --filter=test_checkout_sin_sesion
}

/*
php artisan test tests/Feature/CarritoControllerTest.php
*/