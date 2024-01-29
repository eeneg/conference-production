<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Attachment;

class UploadFileHandlerService {



    public function fileHandle($data){
        $files = [];
        foreach($data['files'] as $e){
            array_push($files, [
                'file_name' =>str_replace(' ','_',$e->getClientOriginalName()),
                'storage_id' => $data['storage_id'],
                'category_id' => $data['category_id'],
                'date' => $data['date'],
                'path' => 'public/File_Uploades/' . $data['storage_id'] .'/'. str_replace(' ','_',$e->getClientOriginalName()),
                'details' => $data['details']
            ]);
            Storage::putFileAs('public/File_Uploades/' . $data['storage_id'] . '/', $e, str_replace(' ','_',$e->getClientOriginalName()));
        }
        return $files;
    }
}
