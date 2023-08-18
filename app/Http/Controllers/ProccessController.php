<?php

namespace App\Http\Controllers;

use App\Http\Services\ImportService;

class ProccessController extends Controller
{
    public function startImport()
    {
        $csv_import = new ImportService();
        $csv_import->import(public_path('upload') .'/1692363245.csv');
    }
}
