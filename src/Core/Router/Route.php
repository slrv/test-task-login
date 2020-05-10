<?php


namespace Core\Router;


class Route
{
    private $method;
    private $path;
    private $controllerClass;
    private $methodName;
    private $middlewares;

    function __construct(string $method, string $path, string $controllerClass, string $methodName, array $middlewares = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->controllerClass = $controllerClass;
        $this->methodName = $methodName;
        $this->middlewares = $middlewares;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function match(string $path): bool {
        return $this->path === $path;
    }
}