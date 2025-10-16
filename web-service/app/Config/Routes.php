<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// User exposed pages
$routes->get('/', 'Users::showLandingPage');
$routes->get('/mood-board', 'Users::showMoodBoard');
$routes->get('/road-map', 'Users::showRoadMap');
