<?php

namespace App\Services;

use App\Jobs\ProcessAttachment;
use App\Models\Attachment;
use Illuminate\Support\Facades\Process;

class AttachmentService {



    public function job($attachment){
       ProcessAttachment::dispatch($attachment);
    }
}
