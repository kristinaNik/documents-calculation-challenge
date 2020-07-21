<?php


namespace App\Entity\Models;


class Currency
{

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $rate;

    /**
     * Currency constructor.
     *
     * @param $name
     * @param $rate
     */
    public function __construct($name, $rate)
    {
        $this->name = $name;
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

}