<?php


namespace App\Handlers;


class DataHandler
{
    const INVOICE_TYPE = 1;
    const CREDIT_NOTE = 2;
    const DEBIT_NOTE = 3;

    /**
     * @param $invoice
     * @return array
     */
    public function prepareData($invoice) :array
    {
        list($customer, $vat, $number, $type, $parent, $currency, $total) = $invoice;

        return [
            'customer' => $customer,
            'vat' => $vat,
            'number' => $number,
            'type' => $type,
            'parent'=> $parent,
            'currency'=>$currency,
            'total'=>$total
        ];
    }

    /**
     * @param $invoices
     * @return bool
     * @throws \Exception
     */
    public function checkData($invoices): bool
    {
        $validTypes = [self::INVOICE_TYPE, self::CREDIT_NOTE, self::DEBIT_NOTE];

        foreach ($invoices as $key => $invoice) {

            if (!array_key_exists('parent', $invoice)) {
                throw new \Exception("All invoices should have a parent property");
            }
            if (!in_array($invoice['type'], $validTypes)) {
                throw new \Exception("Invoice has an invalid type");
            }

            if (empty($invoice['parent'])) {
                continue;
            }

            if (empty($invoice['parent']) && $invoice['type'] != self::INVOICE_TYPE) {
                throw new \Exception("The invoice has a specified parent property , but does not have a value");
            }
        }
        return true;
    }
}