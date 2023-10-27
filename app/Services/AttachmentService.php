<?php

namespace App\Services;

use App\Jobs\ProcessAttachment;
use App\Models\Attachment;

class AttachmentService {



    public function job($id){
        $attachment = Attachment::doesntHave('pdfcontent')->where('conference_id', $id)->get();
        foreach($attachment as $file){
            ProcessAttachment::dispatch($file);
        }
    }
}
