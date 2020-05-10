<?php


namespace Core\Sanitization\Sanitizers;


use Core\Sanitization\ISanitized;

class EmailSanitizer implements ISanitized
{
    function sanitize($value)
    {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }
}