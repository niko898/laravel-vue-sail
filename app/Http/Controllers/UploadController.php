<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Http\Services\ProcessService;

class UploadController extends Controller
{
    //
    public function request(Request $request, FileReceiver $receiver)
    {
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
    
        $save = $receiver->receive();
        if ($save->isFinished()) {
            $final_file = $this->saveFile($save->getFile());
            if($final_file){
                ProcessService::setJob($final_file);
                return response()->json(['result'=>'You have successfully upload file.']);
            }else{
                return response()->json(['result'=>'Sorry we have some problems']);
            }

        }
    
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            "status" => true
        ]);
    }

    /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function saveFile(UploadedFile $file)
    {
        $fileName = $this->createFilename($file);

        $yearFolder = date('Y');
        $monthFolder = date('m');
        $filePath = "upload/{$yearFolder}/{$monthFolder}/";
        $finalPath = storage_path("app/public/{$filePath}");

        $file->move($finalPath, $fileName);

        return $filePath . $fileName;
    }

    /**
     * Create unique filename for uploaded file
     * @param UploadedFile $file
     * @return string
     */
    protected function createFilename(UploadedFile $file)
    {
        return implode([
            time(),
            mt_rand(100, 999),
            '.',
            $file->getClientOriginalExtension()
        ]);
    }
}