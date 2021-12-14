<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class IframeController extends Controller
{
    public function show($lang, $siteId, $sessionId, $productModel)
    {
//     die('i');
        $data['lang'] = $lang;
        $data['siteId'] = $siteId;
        $data['sessionId'] = $sessionId;
        $data['productModel'] = $productModel;

        $product = Product::where('model', $productModel)->first();

        $data['model_id_2d'] = $product->model_id_2d;
        $data['model_id_3d'] = $product->model_id_3d;

        $data['viz_model_id'] = '';

        if (!empty($product->model_id_2d)) {
            $data['viz_model_id'] = $product->model_id_2d;
        }

        if (!empty($product->model_id_2d) && !empty($product->model_id_3d)) {
            $data['viz_model_id'] = $product->model_id_3d;
        }


//        $client = new \GuzzleHttp\Client();
//        $res = $client->request('GET', 'http://eney-shared-api.demo3.proxy.abtollc.com/api/template/1?token=qweqwe');
//        /* dd(json_decode($res->getBody()->getContents())->template); */
//        $data['vis'] = json_decode($res->getBody()->getContents())->template;

        return view('iframe.show', $data);
    }
}
