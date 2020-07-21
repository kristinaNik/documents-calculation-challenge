<?php


namespace App\Interfaces;


interface CurrencyInterface
{

    public function getCurrencies(string $args): array;
}