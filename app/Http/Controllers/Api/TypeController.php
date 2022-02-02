<?php

namespace App\Http\Controllers\Api;

use App\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;

class TypeController extends Controller
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

        $types = $product->types()
            ->where('status', '=', 1)
            ->get()
            ->map(
                function ($item, $key) use ($lang) {
                    $item->name = $item->getTranslatedName($lang);
                    $item->image = !empty($item->image) ? asset('storage/' . $item->image) : false;
                    return $item;
                }
            );

        return response()->json($types);
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
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        //
    }
}
