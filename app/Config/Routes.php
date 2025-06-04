<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Pages Public Non-Auth Bisa Akses
$routes->get('/', 'PagesController::index');
$routes->get('/home', 'PagesController::index');
$routes->get('/contact', 'PagesController::contact');
$routes->get('/login', 'PagesController::loginPage');
$routes->get('/register', 'PagesController::signupPage');
