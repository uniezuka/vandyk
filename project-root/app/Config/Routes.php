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
$routes->match(['get', 'post'], 'change_password', 'Home::change_password', ['filter' => 'authGuard']);

$routes->get('/login', 'Login::index', ['filter' => 'guestGuard']);
$routes->get('/logout', 'Login::logout', ['filter' => 'authGuard']);
$routes->post('/login', 'Login::authenticate', ['filter' => 'guestGuard']);

$routes->get('/brokers', 'Brokers::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'broker/create', 'Brokers::create', ['filter' => 'authGuard']);
$routes->get('/broker/update/(:num)', 'Brokers::update/$1', ['filter' => 'authGuard']);
$routes->post('/broker/update/(:num)', 'Brokers::update/$1', ['filter' => 'authGuard']);
$routes->get('/broker/change_password/(:num)', 'Brokers::change_password/$1', ['filter' => 'authGuard']);
$routes->post('/broker/change_password/(:num)', 'Brokers::change_password/$1', ['filter' => 'authGuard']);

$routes->get('/sla', 'SLA::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'sla/create', 'SLA::create', ['filter' => 'authGuard']);
$routes->get('/sla/update/(:num)', 'SLA::update/$1', ['filter' => 'authGuard']);
$routes->post('/sla/update/(:num)', 'SLA::update/$1', ['filter' => 'authGuard']);
$routes->post('/sla/reclaim', 'SLA::reclaim', ['filter' => 'authGuard']);

$routes->get('/client/(:num)/building/create', 'ClientBuildings::create/$1', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], '/client/(:num)/building/create', 'ClientBuildings::create/$1', ['filter' => 'authGuard']);
$routes->get('/client/(:num)/building/delete/(:num)', 'ClientBuildings::delete/$1/$2', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], '/client/(:num)/building/update/(:num)', 'ClientBuildings::update/$1/$2', ['filter' => 'authGuard']);
$routes->match(['post'], '/client/(:num)/building/(:num)/mortgage-update', 'ClientBuildings::mortgage_update/$1/$2', ['filter' => 'authGuard']);

$routes->get('/clients', 'Clients::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'client/create', 'Clients::create', ['filter' => 'authGuard']);
$routes->get('/client/details/(:num)', 'Clients::details/$1', ['filter' => 'authGuard']);
$routes->get('/client/update/(:num)', 'Clients::update/$1', ['filter' => 'authGuard']);
$routes->post('/client/update/(:num)', 'Clients::update/$1', ['filter' => 'authGuard']);

$routes->get('/flood_quotes', 'FloodQuote::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'flood_quote/create', 'FloodQuote::create', ['filter' => 'authGuard']);
$routes->get('/flood_quote/update', 'FloodQuote::update', ['filter' => 'authGuard']);
$routes->get('/flood_quote/initial_details/(:num)', 'FloodQuote::intialDetails/$1', ['filter' => 'authGuard']);

$routes->get('/counties', 'Counties::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'county/create', 'Counties::create', ['filter' => 'authGuard']);
$routes->get('/county/update/(:num)', 'Counties::update/$1', ['filter' => 'authGuard']);
$routes->post('/county/update/(:num)', 'Counties::update/$1', ['filter' => 'authGuard']);

$routes->get('/occupancies', 'Occupancies::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'occupancy/create', 'Occupancies::create', ['filter' => 'authGuard']);
$routes->get('/occupancy/update/(:num)', 'Occupancies::update/$1', ['filter' => 'authGuard']);
$routes->post('/occupancy/update/(:num)', 'Occupancies::update/$1', ['filter' => 'authGuard']);

$routes->get('/constructions', 'Constructions::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'construction/create', 'Constructions::create', ['filter' => 'authGuard']);
$routes->get('/construction/update/(:num)', 'Constructions::update/$1', ['filter' => 'authGuard']);
$routes->post('/construction/update/(:num)', 'Constructions::update/$1', ['filter' => 'authGuard']);

$routes->get('/transaction_types', 'TransactionTypes::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'transaction_type/create', 'TransactionTypes::create', ['filter' => 'authGuard']);
$routes->get('/transaction_type/update/(:num)', 'TransactionTypes::update/$1', ['filter' => 'authGuard']);
$routes->post('/transaction_type/update/(:num)', 'TransactionTypes::update/$1', ['filter' => 'authGuard']);

$routes->get('/fire_codes', 'FireCodes::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'fire_code/create', 'FireCodes::create', ['filter' => 'authGuard']);
$routes->get('/fire_code/update/(:num)', 'FireCodes::update/$1', ['filter' => 'authGuard']);
$routes->post('/fire_code/update/(:num)', 'FireCodes::update/$1', ['filter' => 'authGuard']);

$routes->get('/coverages', 'Coverages::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'coverage/create', 'Coverages::create', ['filter' => 'authGuard']);
$routes->get('/coverage/update/(:num)', 'Coverages::update/$1', ['filter' => 'authGuard']);
$routes->post('/coverage/update/(:num)', 'Coverages::update/$1', ['filter' => 'authGuard']);

$routes->get('/insurers', 'Insurers::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'insurer/create', 'Insurers::create', ['filter' => 'authGuard']);
$routes->get('/insurer/update/(:num)', 'Insurers::update/$1', ['filter' => 'authGuard']);
$routes->post('/insurer/update/(:num)', 'Insurers::update/$1', ['filter' => 'authGuard']);
$routes->get('/insurer/activate/(:num)', 'Insurers::activate/$1', ['filter' => 'authGuard']);
$routes->get('/insurer/deactivate/(:num)', 'Insurers::deactivate/$1', ['filter' => 'authGuard']);

$routes->get('/sla_settings', 'SLASettings::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'sla_setting/create', 'SLASettings::create', ['filter' => 'authGuard']);
$routes->get('/sla_setting/update/(:num)', 'SLASettings::update/$1', ['filter' => 'authGuard']);
$routes->post('/sla_setting/update/(:num)', 'SLASettings::update/$1', ['filter' => 'authGuard']);
$routes->get('/sla_setting/set_current/(:num)', 'SLASettings::set_current/$1', ['filter' => 'authGuard']);

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
