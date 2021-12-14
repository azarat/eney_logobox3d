<?php

namespace App\Http\Controllers\Api;

use App\ApplicationType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\ApplicationTypes;
use App\Area;

class ApplicationTypeController extends Controller
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
     * Display a list of the application types
     * selected by product model field
     *
     * @param  string $lang
     * @param  int $productModel
     * @return \Illuminate\http\Response
     */
    public function getByProductModel($lang, $productModel)
    {
        $product = Product::where('model', '=', $productModel)->firstOrFail();

        $applicationTypes = $product->areas()
            ->where('status', 1)
            ->get()
            ->map(
                function ($item, $key) use ($lang) {
                    return $item->applicationTypes()
                        ->where('status', 1)
                        ->get()
                        ->map(
                            function ($item, $key) use ($lang) {
                                $item->name = $item->getTranslatedName($lang);
                                $item->image = !empty($item->image) ? asset('storage/' . $item->image) : false;
                                return $item;
                            }
                        )
                        ->unique();
                }
            )->unique()->flatten(1);

        return response()->json($applicationTypes);
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
     * @param  \App\ApplicationType  $applicationType
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationType $applicationType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ApplicationType  $applicationType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationType $applicationType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ApplicationType  $applicationType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationType $applicationType)
    {
        //
    }
}
