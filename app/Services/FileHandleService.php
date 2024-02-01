<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Attachment;

class FileHandleService {



    public function fileHandle($files, $id){

        $attachments = [];

        foreach($files as $e){
            $category = $e['category'];
            $category_order = $e['category_order'];

            foreach($e['files'] as $key => $file){
                array_push($attachments,
                    [
                        'category'          => $category,
                        'category_order'    => $category_order,
                        'file_name'         => $file['file']->getClientOriginalName(),
                        'path'              => 'Conference_Attachments/' . str_replace(' ', '_',$id . '/' . $category . '/' . $file['file']->getClientOriginalName()),
                        'details'           => $file['file_details'],
                        'storage_id'        => $file['storage_id'],
                        'file_order'        => $file['file_order'],
                    ]
                );
                if(is_file($file['file'])){
                    Storage::putFileAs('public/Conference_Attachments/' . $id . '/' . str_replace(' ', '_', $category), $file['file'], str_replace(' ','_',$file['file']->getClientOriginalName()));
                }
            }
        };

        return $attachments;

    }
}
