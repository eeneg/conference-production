<?php

namespace App\Services;

use App\Models\File;
use App\Models\FileVersionControl;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage as FileStorage;

class DeleteAllFileVersions {

    public function handle($id){
        $fv = FileVersionControl::where('file_id', $id)->first();

        $file_versions = FileVersionControl::where('control_id', $fv->control_id)->get('file_id');

        if(count($file_versions) > 1){
            $files = File::whereIn('id', $file_versions)->get();

            foreach($files as $file){
                FileStorage::delete($file->path);
            }

            if(count(FileStorage::files('public/File_Uploads/'.$files->first()->storage_id)) == 1){
                FileStorage::deleteDirectory('public/File_Uploads/'.$f->storage_id);
            }

            File::whereIn('id', $file_versions)->delete();
        }
    }
}
