<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


 // Pages Harus Terautentikasi Only




 // Pages Public Non-Auth Bisa Akses
$this->get('/', 'PagesController::index');
$this->get('/home', 'PagesController::index');

$this->get('/login', 'PagesController::loginPage');