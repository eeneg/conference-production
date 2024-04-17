<?php

namespace App\Services;

use App\Models\File;
use App\Models\FileVersionControl;
use Ramsey\Uuid\Uuid;

class UpdateNewLatestOnDeleteService {

    public function handle($id){
        $fv = FileVersionControl::where('file_id', $id)->first();

        $file_versions = FileVersionControl::where('control_id', $fv->control_id)->get('file_id');

        $files = File::whereIn('id', $file_versions)->where('latest', false)->latest()->first();

        $files->update(['latest' => true]);
    }
}
