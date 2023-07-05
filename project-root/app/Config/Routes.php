<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'profile', 'Home::profile', ['filter' => 'authGuard']);
$routes->get('/change_password', 'Home::change_password', ['filter' => 'authGuard']);

$routes->get('/login', 'Login::index', ['filter' => 'guestGuard']);
$routes->get('/logout', 'Login::logout', ['filter' => 'authGuard']);
$routes->post('/login', 'Login::authenticate', ['filter' => 'guestGuard']);

$routes->get('/brokers', 'Brokers::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'broker/create', 'Brokers::create', ['filter' => 'authGuard']);
$routes->get('/broker/update/(:num)', 'Brokers::update/$1', ['filter' => 'authGuard']);
$routes->post('/broker/update/(:num)', 'Brokers::update/$1', ['filter' => 'authGuard']);

$routes->get('/settings/transaction_types', 'Settings::transaction_types', ['filter' => 'authGuard']);
$routes->get('/settings/fire_codes', 'Settings::fire_codes', ['filter' => 'authGuard']);
$routes->get('/settings/coverage_list', 'Settings::coverage_list', ['filter' => 'authGuard']);
$routes->get('/settings/insurer_naic_list', 'Settings::insurer_naic_list', ['filter' => 'authGuard']);

$routes->get('/sla', 'SLA::index', ['filter' => 'authGuard']);
$routes->get('/sla/add', 'SLA::add', ['filter' => 'authGuard']);
$routes->get('/sla/edit', 'SLA::edit', ['filter' => 'authGuard']);

$routes->get('/clients', 'Clients::index', ['filter' => 'authGuard']);
$routes->get('/clients/add', 'Clients::add', ['filter' => 'authGuard']);
$routes->get('/clients/details', 'Clients::details', ['filter' => 'authGuard']);
$routes->get('/clients/update', 'Clients::update', ['filter' => 'authGuard']);

$routes->get('/flood_quote/create', 'FloodQuote::Create', ['filter' => 'authGuard']);
$routes->get('/flood_quote/update', 'FloodQuote::Update', ['filter' => 'authGuard']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
