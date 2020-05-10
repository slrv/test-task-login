<?php


namespace Core\Http;


use Core\Config;
use Core\Interfaces\IHasErrorToResponse;
use Exception;

class ErrorResponse
{
    /**
     * @var Response
     */
    private $response;

    public static function fromException(Exception $exception) {
        $response = new ErrorResponse();
        $response->setCodeFromException($exception);
        $response->setBodyFromException($exception);
        $response->send();
    }

    /**
     * ErrorResponse constructor.
     */
    function __construct()
    {
        $this->response = new Response();
    }

    /**
     * @param Exception $exception
     */
    function setCodeFromException(Exception $exception) {
        $this->response->setCode(
            $this->isServerErrorFromException($exception) ? 500 : $exception->getCode()
        );
    }

    /**
     * @param Exception $exception
     */
    function setBodyFromException(Exception $exception) {
        if ($this->isServerErrorFromException($exception)) {
            $error = 'Server Error';
        } elseif ($exception instanceof IHasErrorToResponse) {
            $error = $exception->getErrorToResponse();
        } else {
            $error = 'Error';
        }

        $body = ['error' => $error];

        if (Config::isDevEnv()) {
            $body['message'] = $exception->getMessage();
            $body['trace'] = $exception->getTrace();
        }

        $this->response->setBody($body);
    }

    /**
     * Send response
     */
    function send() {
        $this->response->send();
    }

    /**
     * @param Exception $exception
     * @return bool
     */
    private function isServerErrorFromException(Exception $exception) {
        return $exception->getCode() < 300 || $exception->getCode() > 499;
    }
}