<?php


namespace App\Handlers;


use App\Interfaces\FileInterface;
use App\Interfaces\ValidateInterface;

class FileHandler implements FileInterface, ValidateInterface
{

    /**
     * @param string $file
     * @return array
     * @throws \Exception
     */
    public function getCsvData(string $file): array
    {
        $data = [];
        if ($this->check($file)) {
            $handle = fopen($file, "r");

            while (($row = fgetcsv($handle)) !== FALSE) {
                $data[] = $row;
            }

            fclose($handle);
            unset($data[0]);
        }


        return $data;
    }

    /**
     * @param string $file
     * @return bool
     * @throws \Exception
     */
    public function check(string $file): bool
    {
        if (!file_exists($file)) {
            throw new \Exception('File not found');
        }

        return true;
    }
}