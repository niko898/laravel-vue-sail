<?php

namespace App\Http\Controllers;

use App\Http\Services\ImportService;

class ProccessController extends Controller
{
    public function startImport()
    {
        $import = new ImportService();
        $import->import();
    }
}
