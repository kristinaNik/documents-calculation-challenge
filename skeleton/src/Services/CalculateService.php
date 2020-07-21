<?php


namespace App\Services;


use App\Entity\Models\Invoice;
use App\Handlers\DataHandler;

class CalculateService extends DataHandler
{

    /**
     * @var
     */
    private $defaultCurrency;

    /**
     * @var array
     */
    private $currencyRates = [];

    private $fileData;


    /**
     * @param $fileData
     */
    public function setData($fileData)
    {
        $data = [];
        foreach ($fileData as $invoice)  {
            $data[] = $this->prepareData($invoice);
        }

        $this->fileData =  $data;

        return;
    }


    /**
     * @param $currencyData
     */
   public function setCurrencies($currencyData)
   {
        foreach ($currencyData as $currency) {
            if ($currency->getRate() === 1) {
                $this->setDefaultCurrency($currency->getName());
            }

            $this->currencyRates[$currency->getName()] = $currency->getRate();
        }

        return;
   }


    /**
     * @param string $totalOptions
     * @return array
     */
   public function getTotals($totalOptions = ''): array
   {
        return [];
   }

    /**
     * @return mixed
     */
    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }

    /**
     * @param mixed $defaultCurrency
     */
    public function setDefaultCurrency($defaultCurrency): void
    {
        $this->defaultCurrency = $defaultCurrency;
    }
}