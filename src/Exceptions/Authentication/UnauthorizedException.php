<?php


namespace Exceptions\Authentication;


use Core\Interfaces\IHasErrorToResponse;
use Exception;

class UnauthorizedException extends Exception implements IHasErrorToResponse
{
    const NO_TOKEN = 'no-token';
    const INVALID_TOKEN = 'invalid-token';
    const INVALID_CREDENTIALS = 'invalid-credentials';

    private $details;

    /**
     * UnauthorizedException constructor.
     * @param array|string $details
     */
    public function __construct($details = null)
    {
        parent::__construct('Not authorized', 401);
        $this->details = $details;
    }

    function getErrorToResponse()
    {
        return $this->details;
    }
}