<?php


namespace Core\Router;


use Exceptions\Router\RouteNotFindException;

class Router
{
    const API_PREFIX = 'api';

    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';

    private static $routes = [];

    /**
     * Add GET route to routes list
     *
     * @param string $path
     * @param string $controllerClass
     * @param string $methodName
     * @param array $middlewares
     */
    public static function get(string $path, string $controllerClass, string $methodName, $middlewares = []) {
        self::addRoute(self::GET_METHOD, $path, $controllerClass, $methodName, $middlewares);
    }

    /**
     * Add POST route to routes list
     *
     * @param string $path
     * @param string $controllerClass
     * @param string $methodName
     * @param array $middlewares
     */
    public static function post(string $path, string $controllerClass, string $methodName, $middlewares = []) {
        self::addRoute(self::POST_METHOD, $path, $controllerClass, $methodName, $middlewares);
    }

    /**
     * Find route by method and path
     *
     * @param string $method
     * @param string $path
     * @return Route
     * @throws RouteNotFindException
     */
    public static function findRoute(string $method, string $path): Route {
        $route = null;

        /** @var Route[] $routeList */
        if ($routeList = self::$routes[$method]) {
            $route = array_filter($routeList, function (Route $route) use ($path) {
                return $route->match($path);
            });
        }

        if (!$route || !count($route)) {
            throw new RouteNotFindException($method, $path);
        }

        return array_shift($route);
    }

    /**
     * Add route to routes list
     *
     * @param string $method
     * @param string $path
     * @param string $controllerClass
     * @param string $methodName
     * @param array $middlewares
     */
    private static function addRoute(string $method, string $path, string $controllerClass, string $methodName, $middlewares = []) {
        self::$routes[$method][] = new Route($method, $path, $controllerClass, $methodName, $middlewares);
    }
}