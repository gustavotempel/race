<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;


class CsvImporter
{
    public function __invoke($uploadedFile)
    {
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
            'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv',
            'application/excel', 'application/vnd.msexcel', 'text/plain');

        if (isset($_FILES['file']['tmp_name'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
            if (!in_array($mime, $csvMimes)) {
                finfo_close($finfo);
                throw new UnsupportedMediaTypeHttpException('Incorrect file format');
            }
        }

        $file = fopen($uploadedFile, "r");

        $headers = fgetcsv($file);
        $expectedHeaders = ['fullName', 'distance', 'time', 'ageCategory'];

        if ($headers !== $expectedHeaders) {
            fclose($file);
            throw new BadRequestHttpException("CSV file has incorrect headers.");
        }

        $csvData = array();

        while (($row = fgetcsv($file))) {
            # ToDo: Maybe is a better option to use a DTO to handle and process 'InputRacer' entities instead arrays.
            $csvData[] = array_combine($headers, $row);
        }

        fclose($file);

        return $csvData;
    }
}