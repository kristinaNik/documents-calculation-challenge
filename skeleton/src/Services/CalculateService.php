<?php


namespace App\Services;


use App\Handlers\DataHandler;
use App\Services\Traits\HelperTraits;

class CalculateService extends DataHandler
{
    use HelperTraits;

    /**
     * @var array
     */
    private $currencyRates = [];

    /**
     * @var array
     */
    private $fileData = [];

    /**
     * @param $csvData
     *
     * @throws \Exception
     */
    public function setData($csvData): void
    {
        foreach ($csvData as $invoice) {
            $this->fileData[] = $this->prepareData($invoice);
        }

        if (!$this->checkData($this->fileData)) {
            return;
        };
    }


    /**
     * @param $currencyData
     */
   public function setCurrencies($currencyData): void
   {
        foreach ($currencyData as $currency) {
            if ($currency->getRate() == 1) {
                $this->setDefaultCurrency($currency->getName());
            }

            $this->currencyRates[$currency->getName()] = $currency->getRate();
        }

        return;
   }


    /**
     * @param $vat
     * @param $outputCurrency
     *
     * @return array
     * @throws \Exception
     */
   public function getTotals($vat, $outputCurrency): array
   {
       $result = [];

       $customers = $this->findCustomerByVat($vat);

       foreach ($customers as $vat => $customer) {
           $result[$customer] = round($this->getCustomerTotals($vat, $outputCurrency)) .  " " .$this->getDefaultCurrency();
       }

     return $result;
   }

    /**
     * @param $vat
     * @param $outputCurrency
     * @return float
     *
     * @throws \Exception
     */
   private function getCustomerTotals($vat, $outputCurrency): float
   {
       $total = 0;
       $invoices = [];

        foreach ($this->fileData as $data) {
            if ($vat == $data['vat']) {
                $invoices[$data['number']] = $data;
            }
        }

       $invoiceTotal = $this->generateTotalInvoiceByCurrency($invoices);
       $total += $invoiceTotal * $this->currencyRates[$outputCurrency];

       return $total;
   }

    /**
     * @param $invoices
     * @return float
     * @throws \Exception
     */
   private function generateTotalInvoiceByCurrency($invoices): float
   {
        $number = key($invoices);
        $total = array_map(function ($invoice) use ($number) {
            $ratedTotal = 0;
            if ($invoice['number'] == $number) {
                $ratedTotal = $invoice['total'] / $this->currencyRates[$invoice['currency']];
            }
           return $invoice['type'] == self::CREDIT_NOTE ? self::negative($ratedTotal) : $ratedTotal;
        }, $invoices);
        $total = array_shift($total);

        if ($total < 0) {
            throw new \Exception("The total of all the credit notes is bigger than the sum of the invoice");
        }

        return $total;
   }

}