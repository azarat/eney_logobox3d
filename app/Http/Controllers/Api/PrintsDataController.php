<?php

namespace App\Http\Controllers\Api;

use App\Printing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain;
use App\Session;
use App\PrintingsData;
use App\Product;

class PrintsDataController extends Controller
{
    public function getDuringPrintsData(Request $request, $siteId, $sessionId, $productModel)
    {
        $domain = Domain::getBySiteId($siteId);

        $session = Session::findOrFail($sessionId);

        $product = Product::where('model', $productModel)->first();

        $printingsData = PrintingsData::where('status', 0)
            ->where('session_id', $session->id)
            ->where('product_id', $product->id)
            ->first();

        return response()->json($printingsData);
    }

    public function createOrUpdate(Request $request)
    {
        $product = Product::where('model', $request->productModel)
            ->firstOrFail();

        $session = Session::findOrFail($request->sessionId);

        if (empty($request->printsDataId)) {
            $printsDataId = $this->store($product, $session, $request);
        } else {
            $printsDataId = $this->update($request);
        }

        return response()->json($printsDataId);
    }

    public function store($product, $session, $request)
    {
        $printingData = new PrintingsData();
        $printingData->product_id = $product->id;
        $printingData->session_id = $session->id;
        $printingData->comment = $request->comment;
        $printingData->file_url = $request->fileUrl;
        $printingData->remote_file_url = $request->remoteFileUrl ?? '';
        $printingData->is_file_link = $request->isFileLink ?? '';
        $printingData->save();

        return $printingData->id;
    }

    public function update(Request $request)
    {
        $printingData = PrintingsData::findOrFail($request->printsDataId);
        $printingData->comment = $request->comment;
        $printingData->file_url = $request->fileUrl ?? '';
        $printingData->remote_file_url = $request->remoteFileUrl ?? '';
        $printingData->is_file_link = $request->isFileLink ?? '';
        $printingData->save();

        return $printingData->id;
    }

    public function getPdfData(Request $request, int $print_id, string $lang = 'ua')
    {
//        Create empty object
        $result = new \stdClass();

        $printingData = PrintingsData::find($print_id);

        if ($printingData->is_file_link == 0) {
            $result->fileUrl = $printingData->file_url;
        } else {
            $result->fileUrl = $printingData->remote_file_url;
        }

        if (!empty($printingData->cart_product_id)) {
            $printings = Printing::where('product_id', $printingData->product_id)
                ->where('session_id', $printingData->session_id)
                ->where('cart_product_id', $printingData->cart_product_id)
                ->get();
        } else {
            $printings = Printing::where('product_id', $printingData->product_id)
                ->where('session_id', $printingData->session_id)
                ->get();
        }

        $result->model = $printingData->product()->model;
        $result->comment = $printingData->comment;

        $result->printings = $printings->reduce(function($carry, $printItem) use ($lang) {
            $item = new \stdClass();

            $item->colorsQty = $printItem->colors_qty;
            $item->copiesQty = $printItem->colors_qty;

            $area = $printItem->area()->first();
            $item->areaName = $area->getTranslatedName($lang);

            $area = $printItem->applicationType()->first();
            $item->applicationTypeName = $area->getTranslatedName($lang);

            $carry[] = $item;

            return $carry;
        }, []);

        return response()->json($result);
    }
}
