<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::storeRegistration');
$routes->post('/logout', 'AuthController::logout', ['filter' => 'auth']);

$routes->group('employe', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'EmployeController::dashboard');
    $routes->get('demandes', 'EmployeController::index');
    $routes->get('demandes/nouvelle', 'EmployeController::create');
    $routes->get('profil', 'EmployeController::profile');
});

$routes->group('rh', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'RhController::dashboard');
    $routes->get('demandes', 'RhController::index');
});

$routes->group('admin', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('employes', 'AdminController::employes');
});
