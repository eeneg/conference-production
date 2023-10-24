<?php

namespace App\Jobs;

use App\Models\Attachment;
use App\Models\PdfContent;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;

class ProcessAttachment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle($attachment)
    {
        try{
            $py = Process::path(app_path('/Python/'))->forever()->run('python3 app.py '.escapeshellarg($attachment->path . '/' . $attachment->file_name));
        }catch(Exception $e){

        }

        $res = $py->output();
        $er = $py->errorOutput();

        PdfContent::create(['attachment_id' => $attachment->id, 'content' => $res]);
    }
}
