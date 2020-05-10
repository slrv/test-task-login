<?php


namespace Core\Http;


use Core\Interfaces\Arrayable;
use Serializable;

class Response
{
    private $code = 200;
    private $body;
    private $headers = [
        "Access-Control-Allow-Origin" => "*",
        "Access-Control-Allow-Methods" => "GET, POST, OPTIONS",
        "Access-Control-Allow-Headers" => "Content-type, Authorization"
    ];

    /**
     * @param mixed $body
     * @return Response
     */
    public function setBody($body): Response
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param mixed $code
     * @return Response
     */
    public function setCode($code): Response
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set header to response
     *
     * @param string $name
     * @param $value
     * @return Response
     */
    public function setHeader(string $name, $value): Response {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Send response
     */
    public function send() {
        $this->setHeader("Content-Type","application/json; charset=UTF-8;");

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        http_response_code($this->code);

        if (is_array($this->body)) {
            $response = json_encode($this->body);
        } elseif (is_string($this->body)) {
            $response = $this->body;
        } elseif (is_object($this->body) && $this->body instanceof Serializable) {
            $response = $this->body->serialize();
        } elseif (is_object($this->body) && $this->body instanceof Arrayable) {
            $response = json_encode($this->body->toArray());
        } else {
            return;
        }

        echo $response;
    }
}