<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class ImportService 
{
    public function import(): void
    {
        DB::disableQueryLog();
        //DB::table('csv')->truncate();
  
        LazyCollection::make(function () {
            $handle = fopen(public_path("/upload/1692363245.csv"), 'r');
            
            while (($line = fgetcsv($handle, 4096)) !== false) {
                $dataString = implode(", ", $line);
                $row = explode(';', $dataString);
                yield $row;
            }
  
            fclose($handle);
  
        })
        ->skip(1)
        ->chunk(1000)
        ->each(function (LazyCollection $chunk) {
  
            $records = $chunk->map(function ($row) {
                          return [
                            "word_0" => $row[0],
                            "word_1" => $row[1],
                            "word_2" => $row[2],
                            "word_3" => $row[3],
                            "word_4" => $row[4],
                            "word_5" => $row[5],
                            "word_6" => $row[6],
                            "word_7" => $row[7]
                          ];
                      })->toArray();
            
            DB::table('csv')->insert($records);
  
        });
    }
}
