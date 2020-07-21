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

}