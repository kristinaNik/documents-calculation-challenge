<?php


namespace App\Handlers;


class DataHandler
{

    /**
     * @param $invoice
     * @return array
     */
    public function prepareData($invoice) :array
    {
        list($customer, $vat, $number, $type, $parent, $currency, $total) = $invoice;

        $invoiceData[] = [
            'customer' => $customer,
            'vat' => $vat,
            'number' => $number,
            'type' => $type,
            'parent'=> $parent,
            'currency'=>$currency,
            'total'=>$total
        ];

        return $invoiceData;
    }

}