<?php


namespace Core\Interfaces;


use Core\Http\ErrorResponse;
use Exception;

interface IExecutable
{
    /**
     * Method execute proceed some logic. Must return boolean result
     * Can throw exceptions
     * @see ErrorResponse::setBodyFromException()
     *
     * @return bool
     * @throws Exception
     */
    function execute(): bool ;
}