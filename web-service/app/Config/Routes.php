<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// User exposed pages
$routes->get('/', 'Users::showLandingPage');
$routes->get('/mood-board', 'Users::showMoodBoard');
$routes->get('/road-map', 'Users::showRoadMap');

// Auth
$routes->get('/login', 'Auth::showLoginPage');
$routes->post('/login', 'Auth::login');
$routes->post('/logout', 'Auth::logout');
