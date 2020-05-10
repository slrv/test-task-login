<?php


namespace Middlewares;


use Core\Http\Request;
use Core\Interfaces\IExecutable;
use Core\Session;
use Exceptions\Authentication\UnauthorizedException;
use Exceptions\Database\ConnectionException;
use Exceptions\Database\ExecutionException;
use Models\User\User;

class AuthenticationMiddleware implements IExecutable
{
    const AUTH_HEADER = 'HTTP_AUTHORIZATION';

    /**
     * @return bool
     * @throws UnauthorizedException
     * @throws ConnectionException
     * @throws ExecutionException
     */
    function execute(): bool
    {
        if (!Request::hasHeader(self::AUTH_HEADER)) {
            throw new UnauthorizedException(UnauthorizedException::NO_TOKEN);
        }

        $user = User::findByToken(Request::getHeaderValue(self::AUTH_HEADER));

        if (!$user) {
            throw new UnauthorizedException(UnauthorizedException::INVALID_TOKEN);
        }

        Session::setValue('user', $user);
        Session::setValue('token', Request::getHeaderValue(self::AUTH_HEADER));
        return true;
    }
}