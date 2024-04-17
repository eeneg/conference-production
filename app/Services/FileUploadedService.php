<?php

namespace App\Services;

use App\Models\File;
use Ramsey\Uuid\Uuid;
use App\Models\FileVersionControl;

class FileUploadedService {
    public function handle($file_id){
        FileVersionControl::create(['control_id' => (string) Uuid::uuid4(), 'file_id' => $file_id]);
    }
}
