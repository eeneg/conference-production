<?php

namespace App\Services;

use App\Models\File;
use App\Jobs\ProcessFileContent;

class FileContentService {



    public function handle($id){
        $file = File::find($id);
        ProcessFileContent::dispatchSync($file);
    }
}
