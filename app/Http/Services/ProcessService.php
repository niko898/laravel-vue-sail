<?php

namespace App\Http\Services;

use App\Jobs\ProcessImport;

class ProcessService 
{
    public static function setJob($filename){
        ProcessImport::dispatch($filename);
    } 
}
