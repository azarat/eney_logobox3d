<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;

class ZProductsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:zproducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from zproducts.xml';

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
//    public function handle()
//    {
//        $remoteFile = 'https://thegifts.com.ua/syncro/zproducts.xml';
//        $localFile = storage_path('app/import/zproducts.xml');
//        copy($remoteFile, $localFile);
//
//        $xml = simplexml_load_file($localFile);
//
//        $count_rows = count($xml->product);
//        $array = (array)$xml;
//        $m = [];
//
//        for ($i = 0; $i < $count_rows; $i++) {
//            $cat_id = (int)preg_replace(
//                "/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/",
//                "",
//                (string)$array['product'][$i]->category
//            );
//
//            $products = Product::where('model', $array['product'][$i]->model)->get();
//
//            if (count($products) < 1) {
//                $product = new Product;
//                $product->processed = '0';
//                $product->model = $array['product'][$i]->model;
//                $product->category = $cat_id;
//                $product->save();
//            }
//        }
//    }
    public function handle()
    {
        $remoteFile = 'https://thegifts.com.ua/syncro/zproducts.json';
        $localFile = storage_path('app/import/zproducts.json');
        copy($remoteFile, $localFile);

        $products = $this->handleJsonFile();


        foreach ($products as $product) {
            if (empty($product) OR !isset($product) OR !isset($product->model) OR empty($product->model)) {
                continue;
            }
            echo $product->model . '<br>';

            $cat_id = (int)preg_replace(
                "/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/",
                "",
                (string)$product->category
            );

            $found_products = Product::where('model', $product->model)->get();

            if (count($found_products) < 1) {
                $new_product = new Product;
                $new_product->processed = '0';
                $new_product->model = $product->model;
                $new_product->category = $cat_id;
                $new_product->save();
            }
        }
    }

    public function handleJsonFile() {
        $file_content = file_get_contents(storage_path('app/import/zproducts.json'));

        $parts = explode('[', $file_content);
        $data = explode(']', $parts[1]);
        $data = str_replace('},', '},=>', $data[0]);
        $items = explode(',=>', $data);

        foreach ($items as $k=>$v) {
            $items[$k] = json_decode($v);
        }

        return $items;
    }
}
