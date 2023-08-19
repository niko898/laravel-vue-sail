<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class ImportService 
{
    public function import($filename)
    {
        LazyCollection::make(function () use ($filename) {
            $file = fopen(storage_path("app/public/{$filename}"), 'r');
            while (($line = fgetcsv($file, 4096)) !== false) {
                $dataString = implode(", ", $line);
                $row = explode(';', $dataString);
                yield $row;
            }
        })->skip(1)->chunk(1000)->each(function (LazyCollection $chunk) {
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

            $bad_rows = [];
            foreach($records as $key_row => $check_row){
                foreach($check_row as $string){
                    if(is_numeric($string)){
                        unset($records[$key_row]);
                        $bad_rows[] = $check_row;
                        break;
                    }
                }
            }
  
            DB::table('csv')->insert($records);
            DB::table('bad_csv')->insert($bad_rows);
        });
        unlink(storage_path("app/public/{$filename}"));
    }
}
