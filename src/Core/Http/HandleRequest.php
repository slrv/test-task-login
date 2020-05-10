<?php


namespace Core\Http;


use Core\Interfaces\IExecutable;
use Core\Router\Route;
use Core\Router\Router;
use Exception;
use Exceptions\Router\RouteNotFindException;

class HandleRequest
{
    public static function handle()
    {
        try {
            $route = self::findRoute();
            foreach ($route->getMiddlewares() as $middleware) {
                self::callMiddleware($middleware);
            }
            $result = self::callControllerMethod($route);
            if ($result instanceof Response) {
                $response = $result;
            } else {
                $response = new Response();
                $response->setBody($result);
            }

            $response->send();
        } catch (Exception $exception) {
            error_log(get_class($exception));
            error_log($exception->getMessage());
            error_log($exception->getTraceAsString());
            ErrorResponse::fromException($exception);
        }
    }

    /**
     * Find route from route list using request data
     *
     * @return Route
     * @throws RouteNotFindException
     */
    private static function findRoute() {
        return Router::findRoute(
            Request::getMethod(),
            Request::getUri()
        );
    }

    /**
     * Call middleware
     *
     * @param string $middleware
     * @throws Exception
     */
    private static function callMiddleware(string $middleware) {
        $instance = new $middleware;
        if ($instance instanceof IExecutable) {
            $instance->execute();
        }
    }

    /**
     * Call controller method
     *
     * @param Route $route
     * @return mixed
     */
    private static function callControllerMethod(Route $route) {
        $className = $route->getControllerClass();
        $methodName = $route->getMethodName();
        $controller = new $className;
        return $controller->$methodName();
    }
}