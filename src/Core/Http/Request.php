<?php


namespace Core\Http;


class Request
{
    /**
     * Get uri of request
     *
     * @return string|string[]
     */
    public static function getUri()
    {
        return str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
    }

    /**
     * Return if request has header
     *
     * @param string $name
     * @return bool
     */
    public static function hasHeader(string $name): bool
    {
        return !empty($_SERVER[$name]);
    }

    /**
     * Return header value
     *
     * @param string $name
     * @return string|null
     */
    public static function getHeaderValue(string $name): ?string
    {
        return $_SERVER[$name] ?? null;
    }

    /**
     * Get request method
     *
     * @return mixed
     */
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get request body
     *
     * @return array|mixed
     */
    public static function getBody()
    {
        $body_str = file_get_contents('php://input');
        $body_arr = json_decode($body_str, true);
        return (
            json_last_error() === JSON_ERROR_NONE &&
            is_array($body_arr)
        ) ? $body_arr : [];
    }

    /**
     * Get request data from GET and POST fields
     *
     * @return array
     */
    public static function getFields()
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * Get files from request
     *
     * @return array
     */
    public static function getFiles()
    {
        return $_FILES;
    }

    /**
     * Get all data from request
     *
     * @return array
     */
    public static function getAllDataFromRequest(): array {
        return array_merge(
            self::getFields(), self::getBody(), self::getFiles()
        );
    }
}