<?php


namespace App\Services;


use App\Handlers\DataHandler;
use App\Services\Traits\HelperTraits;

class CalculateService extends DataHandler
{
    use HelperTraits;

    const INVOICE_TYPE = 1;
    const CREDIT_NOTE = 2;
    const DEBIT_NOTE = 3;


    /**
     * @var
     */
    private $defaultCurrency;

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
     */
    public function setData($csvData): void
    {
        foreach ($csvData as $invoice)  {

           $this->fileData[] = $this->prepareData($invoice);

        }

        return;
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
     * @return array
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
     */
   private function getCustomerTotals($vat, $outputCurrency): float
   {
       $total = 0;
       $invoices = [];

        foreach ($this->fileData as $data) {
            if ($vat == $data['vat']) {

                $invoices[] = $data;
            }
        }

       $invoiceTotal = $this->generateTotalInvoiceByCurrency($invoices);

       $total += $invoiceTotal * $this->currencyRates[$outputCurrency];

       return $total;
   }

    /**
     * @param $invoices
     * @return float|int
     */
   private function generateTotalInvoiceByCurrency($invoices): float
   {
       $total = 0;
       foreach ($invoices as $invoice) {
           $ratedTotal = $invoice['total'] / $this->currencyRates[$invoice['currency']];

           if ($invoice['type'] == self::CREDIT_NOTE) {
               self::negative($ratedTotal);
           }

           $total += $ratedTotal;
       }


       return $total;
   }



    /**
     * @param mixed $defaultCurrency
     */
    public function setDefaultCurrency($defaultCurrency): void
    {
        $this->defaultCurrency = $defaultCurrency;
    }

    /**
     * @return mixed
     */
    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }
}