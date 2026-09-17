<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Public\InicioController;
use App\Http\Controllers\Public\CatalogoController;
use App\Http\Controllers\Public\CarritoController;
use App\Http\Controllers\Public\CheckoutController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LibroController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\PromocionController;
use App\Http\Controllers\Admin\CorreoAutomaticoController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Admin\ClienteController;

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminPasswordController;

use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\Public\PagoController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', [InicioController::class, 'index'])
    ->name('inicio');


Route::get('/catalogo', [CatalogoController::class, 'index'])
    ->name('catalogo');


Route::get('/carrito', [CarritoController::class, 'index'])
    ->name('carrito.index');


Route::post(
    '/carrito/libro/{libro}',
    [CarritoController::class, 'agregarLibro']
)->name('carrito.libro.agregar');


Route::post(
    '/carrito/kit/{promocion}',
    [CarritoController::class, 'agregarKit']
)->name('carrito.kit.agregar');


Route::patch(
    '/carrito/{clave}/cantidad',
    [CarritoController::class, 'actualizarCantidad']
)->name('carrito.cantidad');


Route::delete(
    '/carrito/{clave}',
    [CarritoController::class, 'eliminar']
)->name('carrito.eliminar');


Route::delete(
    '/carrito',
    [CarritoController::class, 'vaciar']
)->name('carrito.vaciar');


/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

Route::get(
    '/checkout',
    [CheckoutController::class, 'index']
)->name('checkout.index');


Route::post(
    '/checkout/mercadopago',
    [MercadoPagoController::class, 'crearOrden']
)->name('mercadopago.crear');


/*
|--------------------------------------------------------------------------
| RESULTADOS DEL PAGO
|--------------------------------------------------------------------------
*/

Route::get(
    '/checkout/exito',
    [PagoController::class, 'exito']
)->name('pago.exito');


Route::get(
    '/checkout/pendiente',
    [PagoController::class, 'pendiente']
)->name('pago.pendiente');


Route::get(
    '/checkout/error',
    [PagoController::class, 'error']
)->name('pago.error');


/*
|--------------------------------------------------------------------------
| WEBHOOK MERCADO PAGO
|--------------------------------------------------------------------------
*/

Route::post(
    '/checkout/webhook',
    [MercadoPagoController::class, 'webhook']
)->name('mercadopago.webhook');


/*
|--------------------------------------------------------------------------
| DETALLE DE LIBROS Y KITS
|--------------------------------------------------------------------------
*/

Route::get(
    '/libro/{libro}',
    [CatalogoController::class, 'show']
)->name('libro.show');


Route::get(
    '/kit/{promocion}',
    [InicioController::class, 'showKit']
)->name('kit.show');


/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN DEL ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Admin no autenticado
        |--------------------------------------------------------------------------
        */

        Route::middleware('guest:admin')->group(function () {

            Route::get('/login', [
                AdminLoginController::class,
                'showLogin'
            ])->name('login');


            Route::post('/login', [
                AdminLoginController::class,
                'login'
            ])->name('login.store');


            Route::get('/forgot-password', [
                AdminPasswordController::class,
                'showForgotPassword'
            ])->name('password.request');


            Route::post('/forgot-password', [
                AdminPasswordController::class,
                'sendResetLink'
            ])->name('password.email');


            Route::get('/reset-password/{token}', [
                AdminPasswordController::class,
                'showResetPassword'
            ])->name('password.reset');


            Route::post('/reset-password', [
                AdminPasswordController::class,
                'resetPassword'
            ])->name('password.update');
        });


        /*
        |--------------------------------------------------------------------------
        | Admin autenticado
        |--------------------------------------------------------------------------
        */

        Route::middleware('auth:admin')->group(function () {

            Route::get('/', [
                DashboardController::class,
                'index'
            ])->name('dashboard');


            Route::resource(
                'libros',
                LibroController::class
            )->except('show');


            Route::resource(
                'categorias',
                CategoriaController::class
            )->except('show');


            Route::resource(
                'promociones',
                PromocionController::class
            )
                ->except('show')
                ->parameters([
                    'promociones' => 'promocion',
                ]);


            Route::get('/correos', [
                CorreoAutomaticoController::class,
                'index'
            ])->name('correos.index');


            Route::put('/correos/{correo}', [
                CorreoAutomaticoController::class,
                'update'
            ])->name('correos.update');


            Route::get('/ventas', [
                VentaController::class,
                'index'
            ])->name('ventas.index');


            Route::get('/clientes', [
                ClienteController::class,
                'index'
            ])->name('clientes.index');


            Route::post('/logout', [
                AdminLoginController::class,
                'logout'
            ])->name('logout');
        });
    });