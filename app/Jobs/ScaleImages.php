<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Images;
use App\Components\UploadMedia;

class ScaleImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $files = Images::whereNull('scaled_filename')->get();
        $records = [];
        foreach ($files as $key => $value) {
            $records[$key]['scaled_filename'] = (new UploadMedia)->scaleImage($value->filename, $value->scale_size);
            $records[$key]['id'] = $value->id;
            Images::whereId($value->id)->update(['scaled_filename' => $records[$key]['scaled_filename'] ]);
        }
    }
}
