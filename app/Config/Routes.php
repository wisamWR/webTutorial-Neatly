<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes publik (tanpa filter)
$routes->get('logout', 'AuthController::logout');
$routes->get('presentation/(:segment)/data', 'PresentationController::data/$1');
$routes->get('presentation/(:segment)', 'PresentationController::view/$1');

// Routes untuk guest (belum login) — redirect ke / kalau sudah login
$routes->group('', ['filter' => 'guest'], function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::login');
});

// Routes yang butuh login
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    
    // Tutorial CRUD
    $routes->get('tutorial', 'TutorialController::index');
    $routes->get('tutorial/create', 'TutorialController::create');
    $routes->post('tutorial/store', 'TutorialController::store');
    $routes->get('tutorial/edit/(:num)', 'TutorialController::edit/$1');
    $routes->post('tutorial/update/(:num)', 'TutorialController::update/$1');
    $routes->get('tutorial/delete/(:num)', 'TutorialController::delete/$1');
    $routes->get('tutorial/detail/(:num)', 'TutorialController::detail/$1');
    
    // Detail CRUD
    $routes->post('detail/store/(:num)', 'DetailController::store/$1');
    $routes->get('detail/edit/(:num)', 'DetailController::edit/$1');
    $routes->post('detail/update/(:num)', 'DetailController::update/$1');
    $routes->get('detail/delete/(:num)', 'DetailController::delete/$1');
    $routes->get('detail/toggle/(:num)', 'DetailController::toggle/$1');
    $routes->get('detail/move/(:num)/(:segment)', 'DetailController::move/$1/$2');
});
