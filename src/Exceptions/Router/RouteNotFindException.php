<?php


namespace Exceptions\Router;


use Exception;

class RouteNotFindException extends Exception
{
    private $method;
    private $path;

    public function __construct(string $method, string $path)
    {
        parent::__construct('Route not found', 404);

        $this->method = $method;
        $this->path = $path;
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
}