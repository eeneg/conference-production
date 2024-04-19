<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Conference;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class AttachmentEditService {



    public function handle($attachments, $conf) : void{

        $old_attachments = [];
        $new_attachments = [];


        foreach($attachments as $items){
            foreach($items['files'] as $key => $files){
                if(array_key_exists('conference_id', $files)){
                    $files['file_order'] = $key + 1;
                    array_push($old_attachments, $files['id']);
                    Attachment::find($files['id'])->update($files);
                }else{
                    array_push($new_attachments, [
                        'file_id'           => $files['id'],
                        'category'          => $items['category'],
                        'category_order'    => $items['category_order'],
                        'file_order'        => $key + 1,
                    ]);
                }
            }
        }

        Attachment::whereNotIn('id', $old_attachments)->delete();

        Conference::find($conf->id)->attachment()->createMany($new_attachments);

    }

}
