<?php


namespace App\Interfaces;


interface FileInterface
{
    public function getCsvData(string $args): array;
}