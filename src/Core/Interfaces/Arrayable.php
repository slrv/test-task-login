<?php


namespace Core\Interfaces;


interface Arrayable
{
    /**
     * Return array representation of class
     *
     * @return array
     */
    function toArray(): array;
}