<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\PdfContent;
use Illuminate\Support\Facades\Storage;

class AttachmentEditService {



    public function handle($attachments, $conf) : void{

        $request_categories = [];

        $request_file = [];

        if($attachments && count($attachments) > 0){

            foreach($attachments as $key => $data){

                array_push($request_categories, $data['category']);

                foreach($data['files'] as $key => $file){
                    $file['file_order'] = $key;
                    $file['details'] = $file['file_details'];
                    if(isset($file['id'])){
                        array_push($request_file, $file['id']);
                        Attachment::find($file['id'])->update($file);
                    }else{
                        $new_file = [
                            'category'          => $data['category'],
                            'category_order'    => $data['category_order'],
                            'file_name'         => $file['file']->getClientOriginalName(),
                            'path'              => str_replace(' ', '_', $conf->id . '/' . $data['category'] . '/' . $file['file']->getClientOriginalName()),
                            'details'           => $file['file_details'],
                            'storage_id'  => $file['storage_id'],
                            'file_order'        => $file['file_order'],
                        ];
                        $newFile = $conf->attachment()->create($new_file);
                        array_push($request_file, $newFile->id);
                        Storage::putFileAs('public/Conference_Attachments/' . $conf->id . '/' . str_replace(' ', '_', $data['category']), $file['file'], str_replace(' ', '_', $file['file']->getClientOriginalName()));
                    }
                }
            }

        }

        $existing_categories = $conf->attachment->pluck('category')->unique()->values();

        $files = Attachment::where('conference_id', $conf->id)->whereNotIn('id', $request_file)->get();

        if(count($files)){
            PdfContent::whereHas('attachment', fn ($q) => $q->whereConferenceId($conf->id)->whereNotIn('id', $request_file))
                ->get('id')
                ->each
                ->delete();

            Attachment::where('conference_id', $conf->id)->whereNotIn('id', $request_file)
                ->get('id')
                ->each
                ->delete();
        }

        foreach(array_diff($existing_categories->toArray(), $request_categories) as $folders){
            Storage::deleteDirectory('public/Conference_Attachments/'. $conf->id . '/' . $folders);
        }

    }

}
