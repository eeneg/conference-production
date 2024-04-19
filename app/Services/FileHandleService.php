<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Attachment;

class FileHandleService {



    public function fileHandle($files){

        $attachments = [];

        foreach($files as $file){
            foreach($file['files'] as $key => $item){
                array_push($attachments, [
                    'file_id' => $item['id'],
                    'category' => $file['category'],
                    'category_order' => $file['category_order'] + 1,
                    'file_order' => $key + 1,
                ]);
            }
        }

        return $attachments;

    }
}
