<?php


namespace App\Handlers;


use App\Interfaces\FileInterface;

class FileHandler implements FileInterface
{
    /**
     * @param string $file
     *
     * @return array
     * @throws \Exception
     */
    public function getCsvData(string $file): array
    {
        if (!file_exists($file)) {
            throw new \Exception('File not found');
        }
        $data = [];
        $handle = fopen($file, "r");


        while (($row = fgetcsv($handle)) !== FALSE) {
            $data[] = $row;
        }
        fclose($handle);
        unset($data[0]);

        return $data;
    }
}