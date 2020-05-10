<?php

use Core\Http\HandleRequest;
use Core\Http\Request;
use Core\Http\Response;

if (Request::getMethod() === 'OPTIONS') {
    $response = new Response();
    $response->setCode(204)->send();
    exit(0);
}

/**
 * Setup env variables
 */
require __DIR__.'/setup/env.php';

/**
 * Register routes
 */
require __DIR__.'/setup/routes.php';

/**
 * Handle HTTP request
 */
HandleRequest::handle();