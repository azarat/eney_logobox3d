<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Printing;
use App\Domain;
use App\Session;
use App\PrintingsData;
use GuzzleHttp;

class CalcController extends Controller
{
    public function index(Request $request, $lang, $siteId, $sessionId)
    {
        $domain = Domain::getBySiteId($siteId);

        $session = Session::findOrFail($sessionId);

        $printings = $session->printings()->get();
        $printingsGroupedByProductCartId = $printings->groupBy('cart_product_id');

        $result = [
            'total' => 0,
            'printings' => [],
            'printingsData' => [],
        ];

        foreach ($printingsGroupedByProductCartId as $cartProductId => $printings) {
            if (!empty($request->products[$cartProductId])) {
                $dataForGroup = $this->getPrintingPriceForGroup($printings, $request->products[$cartProductId], $lang);
                $result['total'] += $dataForGroup['total'];
                $result['printings'][$cartProductId] = $dataForGroup['printings'];
            }
        }

        $printingsData = PrintingsData::where('session_id', $sessionId)
            ->where('status', 1)
            ->get();

        foreach ($printingsData as $printingData) {
            $client = new GuzzleHttp\Client();

            $res = $client->request(
                'GET',
                config('app.visUrl') . '/api/asset-links/' . $printingData->id . '?token=' . config('app.visToken')
            );

            $printingData->pdf = '';
            $printingData->croppedImage = '';

            if ($res->getStatusCode() == 200) {
                $body = json_decode($res->getBody(), true);

                $printingData->pdf = $body['pdf'];
                $printingData->croppedImage = $body['croppedImage'];
            }

            $result['printingsData'][$printingData->cart_product_id] = $printingData;
        }

        return response()->json($result);
    }

    public function onlyTotal(Request $request, $lang, $siteId, $sessionId)
    {
        $domain = Domain::getBySiteId($siteId);

        $session = Session::findOrFail($sessionId);

        $printings = $session->printings()->get();
        $printingsGroupedByProductCartId = $printings->groupBy('cart_product_id');

        $priceByProduct = [
            'total' => 0,
        ];

        foreach ($printingsGroupedByProductCartId as $cartProductId => $printings) {
            $dataForGroup = $this->getPrintingPriceForGroup($printings, $cartProductId, $lang);
            $priceByProduct['total'] += $dataForGroup['total'];
        }


        return response()->json($priceByProduct);
    }

    private function getPrintingPriceForGroup($printings, $productQuantity, $lang) {
        $printingsGroupedByApplicationId = $printings->groupBy('application_type_id');

        $roastingPriceByPrinting = [];

        foreach ($printingsGroupedByApplicationId as $printingsGroupItem) {
            $result = $printingsGroupItem->reduce(
                function ($carry, $printing) {
                    $area = $printing->area()->first();

                    if ($area->roasting_price > $carry['price']) {
                        $carry['price'] = $area->roasting_price;
                        $carry['print_id'] = $printing->id;
                    }

                    return $carry;
                },
                [
                    'price' => 0,
                    'print_id' => null
                ]
            );

            $roastingPriceByPrinting[] = $result;
        }

        $response = $printings->reduce(
            function ($carry, $printing) use ($roastingPriceByPrinting, $productQuantity, $lang) {
                $area = $printing->area()->first();

                $prepare            = $area->prepare_price;
                $kx                 = $area->kx;
                $n                  = $printing->colors_qty;
                $quantity           = $productQuantity;
                $print              = $area->print_price;
                $kz                 = $area->kz;
                $sticking           = $area->sticking_price;
                $copy               = $printing->copies_qty;
                $roasting_max_price = collect($roastingPriceByPrinting)->firstWhere('print_id', $printing->id)['price'];

                $printing->price = $this->getPrintingPrice($prepare, $kx, $n, $quantity, $print, $kz, $sticking, $copy, $roasting_max_price);

                $area = $printing->area()->first();

                $printing->name = $area->getTranslatedName($lang);
                $printing->area_code = $area->code;

                $applicationType = $printing->applicationType()->first();
                $printing->application_type_code = $applicationType->code;
                $printing->application_type_time = $applicationType->time;

                $printing->application_type_name = $applicationType->getTranslatedName($lang);

                $printing->price = round($printing->price, 4);

                $carry['total'] = $carry['total'] + round($printing->price, 4);
                $carry['printings'][] = $printing->only(
                    [
                        'id',
                        'name',
                        'application_type_name',
                        'price',
                        'colors_qty',
                        'copies_qty',
                        'area_id',
                        'area_code',
                        'application_type_id',
                        'application_type_code',
                        'application_type_time',
                    ]
                );

                return $carry;
            },
            [
                'total' => 0,
                'printings' => []
            ]
        );

        return $response;
    }

    private function getPrintingPrice($prepare, $kx, $n, $quantity, $print, $kz, $sticking, $copy, $roasting_max_price)
    {
        if ($roasting_max_price) {
            return (float)(($prepare * (1 + $kx * ($n - 1))) + $quantity * (($print * (1 + $kz * ($n - 1)) + $sticking) * $copy + $roasting_max_price));
        } else {
            return (float)(($prepare * (1 + $kx * ($n - 1))) + $quantity * (($print * (1 + $kz * ($n - 1)) + $sticking) * $copy));
        }
    }
}
