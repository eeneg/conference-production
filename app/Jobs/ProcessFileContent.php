<?php

namespace App\Jobs;

use App\Models\PdfContent;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;

class ProcessFileContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $file = $this->file;

        try{
            $py = Process::path(app_path('/Python/'))->forever()->run('python3 app.py '.escapeshellarg(substr($file->path, 7)));
        }catch(Exception $e){

        }

        $res = $py->output();
        $er = $py->errorOutput();

        $file->pdfContent()->create(['content' => $res]);

        $file->load('pdfContent')->searchable();
    }
}
