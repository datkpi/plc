<?php

namespace App\Imports;

use Spatie\SimpleExcel\Importers\Importer;

class MailImport extends Importer
{
    public function getRows()
    {
        return $this->getRowsFromFile();
    }
}
