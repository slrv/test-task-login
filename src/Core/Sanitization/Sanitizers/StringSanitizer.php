<?php


namespace Core\Sanitization\Sanitizers;


use Core\Sanitization\ISanitized;

class StringSanitizer implements ISanitized
{

    function sanitize($value)
    {
        return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
}