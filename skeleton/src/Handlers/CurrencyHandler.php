<?php


namespace App\Handlers;

use App\Entity\Models\Currency;
use App\Interfaces\CurrencyInterface;

class CurrencyHandler implements CurrencyInterface
{
    /**
     * @param string $arg
     *
     * @return array
     */
    public function getCurrencies(string $arg): array
    {
        $data = [];
        $currencies = explode(',', $arg);
        foreach ($currencies as $currency)
        {
            $split = explode(':', $currency);
            $data[] = new Currency($split[0], $split[1]);
        }

        return $data;

    }
}