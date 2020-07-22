<?php


namespace App\Interfaces;


interface CurrencyInterface
{

    /**
     * @param string $args
     *
     * @return array
     */
    public function getCurrencies(string $args): array;
}