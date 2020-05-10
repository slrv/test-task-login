<?php


namespace Core\Interfaces;


interface IHasErrorToResponse
{
    /**
     * Returns data which be appended to response body
     *
     * @return string|array
     */
    function getErrorToResponse();
}