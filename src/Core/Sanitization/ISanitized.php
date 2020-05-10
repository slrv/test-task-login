<?php


namespace Core\Sanitization;


interface ISanitized
{
    /**
     * Sanitize value
     *
     * @param $value
     * @return mixed
     */
    function sanitize($value);
}