<?php


namespace App\Services\Traits;


trait HelperTraits
{

    /**
     * @var array
     */
    private $customers = [];

    /**
     * @param $val
     * @return int
     */
    public static function negative($val)
    {
        return 0 - $val;
    }

    /**
     * @param $vat
     *
     * @return array
     */
    public function findCustomerByVat($vat): array
    {
        foreach ($this->fileData as $data)
        {
            if ($data['vat'] === $vat)
            {
                $this->customers[$vat] = $data['customer'];
            }
        }

        return $this->customers;
    }

}