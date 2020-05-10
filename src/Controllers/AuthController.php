<?php


namespace Controllers;


use Core\Http\Request;
use Core\Http\Response;
use Core\Session;
use Exception;
use Exceptions\Authentication\UnauthorizedException;
use Exceptions\Database\ConnectionException;
use Exceptions\Database\ExecutionException;
use Exceptions\DTO\WrongPropertySchemaException;
use Exceptions\Validation\IncorrectValidationInstanceException;
use Exceptions\Validation\ValidationException;
use Exceptions\Validation\ValidationNameException;
use Models\User\SessionModel;
use Models\User\User;
use Models\User\UserDTO;

class AuthController
{
    /**
     * Implements login request handler
     *
     * @throws IncorrectValidationInstanceException
     * @throws UnauthorizedException
     * @throws ValidationException
     * @throws WrongPropertySchemaException
     * @throws ConnectionException
     * @throws ValidationNameException
     * @throws Exception
     */
    function signIn() {
        $userDto = UserDTO::getValidDTO(Request::getFields());
        $user = User::findByEmail($userDto->email);
        if (!$user || !password_verify($userDto->password, $user->password)) {
            throw new UnauthorizedException(UnauthorizedException::INVALID_CREDENTIALS);
        }

        return $this->createSession($user);
    }

    /**
     * Implements registration request handler
     *
     * @throws IncorrectValidationInstanceException
     * @throws ValidationException
     * @throws WrongPropertySchemaException
     * @throws Exception
     */
    function signUp() {
        $userData = UserDTO::getValidDTO(Request::getAllDataFromRequest(), false)->getOptions();
        $user = User::create($userData);

        return $this->createSession($user, 201);
    }

    /**
     * Implements me request handler
     *
     * @return User
     */
    function me() {
        return Session::getValue('user');
    }

    /**
     * Implements logout request handler
     *
     * @throws ConnectionException
     * @throws ExecutionException
     */
    function logout() {
        return SessionModel::destroy(
            Session::getValue('token')
        );
    }

    /**
     * Create session for user
     *
     * @param User $user
     * @param int $code
     * @return Response
     * @throws Exception
     */
    private function createSession(User $user, int $code = 200): Response {
        $session = SessionModel::create($user->id);
        return (new Response())
            ->setHeader('Auth-Token', $session->token)
            ->setCode($code)
            ->setBody($user);
    }
}