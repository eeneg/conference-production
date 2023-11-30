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
    public $attachment;

    public function __construct($attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $attachment = $this->attachment;
        try{
            $py = Process::path(app_path('/Python/'))->forever()->run('python3 app.py '.escapeshellarg($attachment->path));
        }catch(Exception $e){

        }

        $res = $py->output();
        $er = $py->errorOutput();

        PdfContent::create(['attachment_id' => $attachment->id, 'content' => $res]);

        Attachment::find($attachment->id)->load('pdfContent')->searchable();
    }
}
