<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->setAutoRoute(true);

// Pages Harus Terautentikasi Only
$routes->get('/dashboard', 'DashboardController::index');
$routes->get('/pages/dashboard/delete/(:segment)', 'DashboardController::delete/$1');
$routes->get('/pages/dashboard/modify/(:segment)', 'DashboardController::modify/$1');
$routes->get('/pages/functions/dashboard_function/create', 'DashboardController::createPage');

// Routes Function Terautentetikasi
$routes->post('/pages/dashboard/create/createFunction', 'DashboardController::createFunction');
$routes->post('/pages/dashboard/update/(:segment)', 'DashboardController::update/$1');

// Pages Public Non-Auth Bisa Akses
$routes->get('/', 'PagesController::index');
$routes->get('/home', 'PagesController::index');
$routes->get('/contact', 'PagesController::contact');
$routes->get('/login', 'PagesController::loginPage');
