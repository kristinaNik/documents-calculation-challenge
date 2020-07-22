<?php


namespace App\Interfaces;


interface FileInterface
{
    /**
     * @param string $args
     *
     * @return array
     */
    public function getCsvData(string $args): array;
}