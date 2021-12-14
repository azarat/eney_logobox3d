<?php
namespace App\Http\Controllers\Api;

use App\Area;
use App\Http\Controllers\Controller;
use App\Product;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller {

    public function manuallyImport(Request $request) {
        $time = time();
        $filename = 'product_area_relation_' . $time . '.csv';
        $path = $request->file('relationsFile')->storeAs('import', $filename);
        $content = File::get(storage_path('app/'.$path));

        $lines = explode("\n", $content);

        array_shift($lines);


        foreach ($lines as $line) {
            $this->handleLine($line);
        }

        return response()->json([
            'status' => 'success',
        ], 200);
    }

    protected function handleLine($line) {
        $line = trim($line);
        if (!isset($line) OR empty($line)) {
            return;
        }

        $parts = explode(",", $line);
        $model = $parts[0];
        $areas = [];
        foreach (explode("|", $parts[1]) as $code) {
            if (!empty($code)) {
                $areas[] = trim($code);
            }
        }

        // get product id
        $product = Product::where('model', $model)->first();
        if (!$product) {
            return;
        }

        // get area ids
        $areas = Area::whereIn('code', $areas)->get()->pluck('id');

        // clear exist relations
        $product->areas()->detach();
        // connect them
        $product->areas()->attach($areas);

        return [
            'product_id' => $product->id,
            'areas' => $areas
        ];
    }

}
