<?php


namespace App\Interfaces;


interface ValidateInterface
{
    /**
     * @param string $data
     * @return bool
     */
    public function check(string $data): bool;
}