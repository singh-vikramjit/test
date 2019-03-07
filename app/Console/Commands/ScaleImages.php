<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ScaleImages as ScaleImageJob;

class ScaleImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scale:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scale the uploaded images';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ScaleImageJob::dispatch();
    }
}
