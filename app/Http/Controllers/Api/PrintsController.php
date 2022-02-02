<?php

namespace App\Http\Controllers\Api;

use App\PrintingsData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Printing;
use App\Product;
use App\Session;
use App\Domain;
use GuzzleHttp;

class PrintsController extends Controller
{
    public function index(Request $request, $lang, $siteId, $sessionId, $productModel)
    {
        $domain = Domain::getBySiteId($siteId);

        $session = Session::findOrFail($sessionId);

        $product = Product::where('model', $productModel)->first();

        $result['printings'] = $session->printings()
            ->select('id', 'session_id', 'colors_qty', 'copies_qty', 'area_id', 'type_id', 'application_type_id', 'status', 'product_id')
            ->where('status', 0)
            ->where('product_id', $product->id)
            ->get()
            ->transform(function($printing) use ($lang) {
                $printing->application_name = $printing->applicationType()->first()->getTranslatedName($lang);
                $printing->area_name = $printing->area()->first()->getTranslatedName($lang);
                $printing->type_name = $printing->type()->first()->getTranslatedName($lang);
                $printing->product_model = $printing->product()->first()->model;
                unset($printing->product_id);
                return $printing;
            });

        $result['printingsData'] = PrintingsData::where('session_id', $sessionId)
            ->where('status', 0)
            ->where('product_id', $product->id)
            ->first();

        $client = new GuzzleHttp\Client();

        $res = $client->request(
            'GET',
            config('app.visUrl') . '/api/asset-links/' . $result['printingsData']->id . '?token=' . config('app.visToken')
        );

        $result['printingsData']->pdf = '';
        $result['printingsData']->croppedImage = '';

        if ($res->getStatusCode() == 200) {
            $body = json_decode($res->getBody(), true);

            $result['printingsData']->pdf = $body['pdf'];
            $result['printingsData']->croppedImage = $body['croppedImage'];
        }

        if (!empty($result['printingsData']->file_url)) {
            $result['printingsData']->file_url = asset(str_replace('public', 'storage', $result['printingsData']->file_url));
        }

        return response()->json($result);
    }

    public function getDuring(Request $request, $siteId, $sessionId, $productModel)
    {
        $domain = Domain::getBySiteId($siteId);

        $session = Session::findOrFail($sessionId);

        $product = Product::where('model', $productModel)->first();

        $printings = $session->printings()
            ->where('status', 0)
            ->where('product_id', $product->id)
            ->get();

        return response()->json($printings);
    }

    public function createOrUpdate(Request $request)
    {
        $product = Product::where('model', $request->productModel)
            ->firstOrFail();

        $session = Session::findOrFail($request->sessionId);

        $relations = [];

        foreach ($request->prints as $printData) {
            if (empty($printData['storedId'])) {
                $relations[$printData['id']] = $this->store($product, $session, $printData);
            } else {
                $relations[$printData['id']] = $this->update($printData);
            }
        }

        return response()->json($relations);
    }

    public function store($product, $session, $printData)
    {
        $printing = new Printing();
        /* DOTO: Add application type and area */
        $printing->product_id = $product->id;
        $printing->session_id = $session->id;
        $printing->colors_qty = $printData['selectedColor'];
        $printing->copies_qty = $printData['selectedCopy'];
        $printing->area_id = $printData['selectedArea'];
        $printing->type_id = $printData['selectedType'];
        $printing->application_type_id = $printData['selectedApplicationType'];
        $printing->save();

        return $printing->id;
    }

    public function update($printData)
    {
        $printing = Printing::findOrFail($printData['storedId']);
        $printing->colors_qty = $printData['selectedColor'];
        $printing->copies_qty = $printData['selectedCopy'];
        $printing->area_id = $printData['selectedArea'];
        $printing->type_id = $printData['selectedType'];
        $printing->application_type_id = $printData['selectedApplicationType'];
        $printing->save();


        return $printing->id;
    }

    public function destroy($siteId, $sessionId, $id)
    {
        $domain = Domain::getBySiteId($siteId);

        $session = Session::findOrFail($sessionId);

        $printing = Printing::findOrFail($id);
        $printing->delete();
    }

    public function confirm($siteId, $sessionId, $productModel, $cartProductId)
    {
        $domain = Domain::getBySiteId($siteId);

        $session = Session::findOrFail($sessionId);

        $product = Product::where('model', $productModel)->first();

        $printings = $session->printings()
            ->where('status', 0)
            ->where('product_id', $product->id)
            ->get();

        if (count($printings) == 0) {
            return response()->json(null, 404);
        }

        $printings->each(
            function ($print) use ($cartProductId) {
                $print->status = 1;
                $print->cart_product_id = $cartProductId;
                $print->save();
            }
        );

        $printsData = PrintingsData::where('session_id', $sessionId)
            ->where('status', 0)
            ->where('product_id', $product->id)
            ->first();

        $printsData->status = 1;
        $printsData->cart_product_id = $cartProductId;
        $printsData->save();
    }
}
