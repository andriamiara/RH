<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::doLogin');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::storeRegistration');
$routes->post('/logout', 'AuthController::logout', ['filter' => 'auth']);

$routes->group('employe', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'EmployeController::dashboard');
    $routes->get('demandes', 'EmployeController::index');
    $routes->get('demandes/nouvelle', 'EmployeController::create');
    $routes->post('demandes', 'EmployeController::storeConge');
    $routes->post('demandes/(:num)/annuler', 'EmployeController::cancel/$1');
    $routes->get('profil', 'EmployeController::profile');
});

$routes->group('rh', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'RhController::dashboard');
    $routes->get('demandes', 'RhController::index');
    $routes->post('demandes/(:num)/approuver', 'RhController::approve/$1');
    $routes->post('demandes/(:num)/refuser', 'RhController::refuse/$1');
    $routes->get('soldes', 'RhController::soldes');
});

$routes->group('admin', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('employes', 'AdminController::employes');
    $routes->get('departements', 'AdminController::departements');
    $routes->get('types-conge', 'AdminController::typesConge');
    $routes->get('soldes', 'AdminController::soldes');
    $routes->post('employes', 'AdminController::storeEmploye');
    $routes->post('employes/(:num)', 'AdminController::updateEmploye/$1');
    $routes->post('employes/(:num)/desactiver', 'AdminController::deactivateEmploye/$1');
    $routes->post('departements', 'AdminController::storeDepartement');
    $routes->post('departements/(:num)', 'AdminController::updateDepartement/$1');
    $routes->post('departements/(:num)/supprimer', 'AdminController::deleteDepartement/$1');
    $routes->post('types-conge', 'AdminController::storeTypeConge');
    $routes->post('types-conge/(:num)', 'AdminController::updateTypeConge/$1');
    $routes->post('types-conge/(:num)/supprimer', 'AdminController::deleteTypeConge/$1');
    $routes->post('soldes', 'AdminController::saveSolde');
});
