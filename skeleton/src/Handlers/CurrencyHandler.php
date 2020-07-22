<?php


namespace App\Handlers;

use App\Entity\Models\Currency;
use App\Interfaces\CurrencyInterface;
use App\Interfaces\ValidateInterface;

class CurrencyHandler implements CurrencyInterface, ValidateInterface
{
    const EUR = 'EUR';
    const USD = 'USD';
    const GBP = 'GBP';

    /**
     * @param string $arg
     *
     * @return array
     * @throws \Exception
     */
    public function getCurrencies(string $arg): array
    {
        $data = [];
        $currencies = explode(',', $arg);
        foreach ($currencies as $currency)
        {
            $split = explode(':', $currency);
            if ($this->check($split[0])) {
                $data[] = new Currency($split[0], $split[1]);
            }
        }

        return $data;
    }

    /**
     * @param string $currency
     * @return bool
     * @throws \Exception
     */
    public function check(string $currency): bool
    {
        $validCurrencies = [self::EUR, self::GBP, self::USD];

        if (!in_array($currency, $validCurrencies)) {
            throw new \Exception('Invalid currency format');
        }

        return true;
    }
}