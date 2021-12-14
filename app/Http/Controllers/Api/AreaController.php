<?php

namespace App\Http\Controllers\Api;

use App\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $lang
     * @param  int $productModel
     * @return \Illuminate\Http\Response
     */
    public function getByProductModel($lang, $productModel)
    {
        $product = Product::where('model', '=', $productModel)
            ->firstOrFail();

        $areas = $product->areas()
            ->where('status', '=', 1)
            ->get()
            ->map(
                function ($item, $key) use ($lang) {
                    $item->name = $item->getTranslatedName($lang);
                    $item->image = !empty($item->image) ? asset('storage/' . $item->image) : false;
                    return $item;
                }
            );

        return response()->json($areas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        //
    }
}
