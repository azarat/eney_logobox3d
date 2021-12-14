<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\ApplicationType;

class ImportPrintTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:printtime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from printtime.xml';

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
        $xml = simplexml_load_string(Storage::get('import/printtime.xml'));

        foreach ($xml->printtime as $printtime) {
            ApplicationType::where('code', $printtime->code)
                ->update([
                    'time'  => $printtime->time,
                ]);
        }
    }
}
