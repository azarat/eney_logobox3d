<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Area;

class ImportPrints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:prints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from prints.xml';

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        if(!$xml = $this->getPrints())
            throw new \Exception('Failed load prints');

//        $xml = simplexml_load_string(Storage::get('import/prints.xml'));

        foreach ($xml->printStyle as $printStyle) {
            Area::where('code', $printStyle->code)
                ->update([
                    'prepare_price'  => $printStyle->preparePrice,
                    'print_price'    => $printStyle->printPrice,
                    'sticking_price' => $printStyle->stickingPrice,
                    'roasting_price' => $printStyle->roastingPrice,
                    'kx'             => $printStyle->kx,
                    'kz'             => $printStyle->kz,
                    'max_colors'     => $printStyle->maxcolors,
                    'max_copy'       => $printStyle->maxCopy,
                ]);
        }
    }

    /**
     * Request data from the print endpoint
     *
     * @return \SimpleXMLElement|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getPrints()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', env('PRINTS_ENDPOINT'), [
            'form_params' => [
                'public_key' => env('PRINTS_PUBLIC_KEY'),
                'private_key' => env('PRINTS_PRIVATE_KEY'),
            ]
        ]);
        $xml = $response->getBody()->getContents();

        return $xml ? simplexml_load_string($xml) : null;
    }
}
