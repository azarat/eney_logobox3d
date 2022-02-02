<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use DB;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the products list
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!empty($request->all())) {
            $data['query'] = '/?' . http_build_query($request->all());
        } else {
            $data['query'] = '';
        }

        $search = $request->get('search') ? $request->get('search') : '';

        $data['search'] = $search;

        $start = $request->get('page') ?? 1;

        $order_get = $request->get('order');
        $order = isset($order_get) ? $request->get('order') : 'id';

        $dir_get = $request->get('order');
        $dir = isset($dir_get) ? $request->get('dir') : 'asc';

        $data['order'] = $order;
        $data['dir'] = $dir;

        $category_get = $request->get('category');
        $data['category'] = isset($category_get) ? $request->get('category') : '*';

        if ($search) {
            $data['products'] = Product::orderBy($order, $dir)
                                        ->skip(10*($start - 1))
                                        ->take(10)
                                        ->where('model', 'like', '%' . $search . '%')
                                        ->get();

            $data['pagination'] = Product::where('model', 'like', '%' . $search . '%')->paginate(10);
        } else {
            if ($data['category'] != '*') {
                $data['products'] = Product::orderBy($order, $dir)
                                            // ->paginate(10);
                                            ->where('category', $data['category'])
                                            ->skip(10*($start - 1))
                                            ->take(10)
                                            ->get();

                $data['pagination'] = Product::where('category', $data['category'])->paginate(10);
            } else {
                $data['products'] = Product::orderBy($order, $dir)
                                            // ->paginate(10);
                                            ->skip(10*($start - 1))
                                            ->take(10)
                                            ->get();

                $data['pagination'] = Product::paginate(10);
            }
        }

        $data['available_categories'] = Product::select('category')
                                                ->orderBy('category')
                                                ->distinct()
                                                ->get()
                                                ->toArray();

        return view('products.product')->with($data);
    }
    /**
     * Show product edit page
     * @param  Int  $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $types = DB::table('application_type')->select('id')->get()->toArray();

        $data['areas'] = array();

        foreach ($types as &$type) {
            $areas = DB::table('area')
                        ->select('id')
                        ->where('application_type_id', $type->id)
                        ->get()
                        ->toArray();

            $area_final = array();

            foreach ($areas as &$area) {
                $area_translations = DB::table('area_translations')
                                        ->where('area_id', $area->id)
                                        ->where('locale', 'ru')
                                        ->get()
                                        ->toArray();

                $translations = array();

                foreach ($area_translations as $area_translation) {
                    $translations[$area_translation->locale] = $area_translation->name;
                }
                $area_final[$area->id] = $translations;
            }

            $type_translations = DB::table('application_type_translations')
                                    ->where('application_type_id', $type->id)
                                    ->where('locale', 'ru')
                                    ->get()
                                    ->toArray();

            $translations = array();

            foreach ($type_translations as $type_translation) {
                $translations[$type_translation->locale] = $type_translation->name;
            }

            $data['areas'][$type->id] = array(
                'areas' => $area_final,
                'translations' => $translations
            );
        }

        $data['types'] = array();

        foreach ($types as &$type) {
            $types = DB::table('type')
                        ->select('id')
                        ->where('application_type_id', $type->id)
                        ->get()
                        ->toArray();

            $_final = array();

            foreach ($types as &$type) {
                $type_translations = DB::table('type_translations')
                                        ->where('type_id', $type->id)
                                        ->where('locale', 'ru')
                                        ->get()
                                        ->toArray();

                $translations = array();

                foreach ($type_translations as $type_translation) {
                    $translations[$type_translation->locale] = $type_translation->name;
                }
                $type_final[$type->id] = $translations;
            }

            $type_translations = DB::table('application_type_translations')
                                    ->where('application_type_id', $type->id)
                                    ->where('locale', 'ru')
                                    ->get()
                                    ->toArray();

            $translations = array();

            foreach ($type_translations as $type_translation) {
                $translations[$type_translation->locale] = $type_translation->name;
            }

            $data['types'][$type->id] = array(
                'types' => $type_final,
                'translations' => $translations
            );
        }

        if (!empty($request->all())) {
            $data['query'] = '/?' . http_build_query($request->all());
        } else {
            $data['query'] = '';
        }

        $data['checked'] = array();
        foreach (DB::table('product_to_area')->select('area_id')
                    ->where('product_id', $id)->get() as $row) {
            $data['checked'][] = $row->area_id;
        }

        $data['checked_type'] = array();
        foreach (DB::table('product_to_type')->select('type_id')
                    ->where('product_id', $id)->get() as $row) {
            $data['checked_type'][] = $row->type_id;
        }

        $data['id'] = $id;
        $data['product'] = Product::where('id', $id)
                                    ->first();
        return view('products.edit')->with($data);
    }
    /**
     * Save product parameters
     * @param  Request $request
     * @return redirect to product list page
     */
    public function save(Request $request)
    {
        $product = $request->post('product-id');

        DB::table('product_to_area')
            ->where('product_id', $product)
            ->delete();

        DB::table('product_to_type')
            ->where('product_id', $product)
            ->delete();

        if ($request->post('area')) {
            foreach ($request->post('area') as $area_id => $area) {
                DB::table('product_to_area')
                    ->insert(['product_id' => $product, 'area_id' => $area_id]);
            }
        }

        if ($request->post('type')) {
            foreach ($request->post('type') as $type_id => $type) {
                DB::table('product_to_type')
                    ->insert(['product_id' => $product, 'type_id' => $type_id]);
            }
        }

        $product = Product::find($product);
        $product->processed = $request->post('processed') ? 1 : 0;
        $product->model_id_2d = $request->model_id_2d;
        $product->model_id_3d = $request->model_id_3d;
        $product->save();

        return redirect('/product');
    }
}
