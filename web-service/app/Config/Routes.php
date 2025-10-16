<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// User exposed pages
$routes->get('/', 'Users::index');
