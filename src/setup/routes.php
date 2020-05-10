<?php

use Controllers\AuthController;
use Core\Router\Router;
use Middlewares\AuthenticationMiddleware;

Router::post('/signIn', AuthController::class, 'signIn');
Router::post('/signUp', AuthController::class, 'signUp');
Router::get('/me', AuthController::class, 'me', [AuthenticationMiddleware::class]);
Router::get('/logout', AuthController::class, 'logout', [AuthenticationMiddleware::class]);