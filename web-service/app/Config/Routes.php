<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public pages
$routes->get('/', 'Users::showLandingPage');
$routes->get('/mood-board', 'Users::showMoodBoard');
$routes->get('/road-map', 'Users::showRoadMap');

// Auth pages
$routes->get('/login', 'Auth::showLoginPage');
$routes->get('/signup', 'Auth::showSignupPage');

// Auth actions
$routes->post('/login', 'Auth::login');
$routes->post('/logout', 'Auth::logout');
$routes->post('/signup', 'Auth::signup');
