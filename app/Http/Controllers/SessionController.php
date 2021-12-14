<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain;
use App\Session;
use App\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SessionController extends Controller
{
    public function init(Request $request, $lang, $domainKey, $productModel)
    {
        try {
            $domain = Domain::where('key', '=', $domainKey)->firstOrFail();
            $product = Product::where('model', '=', $productModel)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(null, 404);
        }

        if ($product->hasArea()) {
            $session = new Session();
            $session->domain_id = $domain->id;
            $session->save();

            return response()->json(
                [
                    'sessionId' => $session->id,
                    'iframeUrl' => config('app.url') . '/' . $lang . '/iframe/' . $domain->key . '/' . $session->id . '/' . $productModel,
                ]
            );
        }

        return response()->json(null, 204);

    }

    public function continue(Request $request, $lang, $domainKey, $productModel, $sessionId)
    {
        try {
            $domain = Domain::where('key', '=', $domainKey)->firstOrFail();
            $product = Product::where('model', '=', $productModel)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(null, 404);
        }

        if ($product->hasArea()) {
            /* if session not exists return error */
            $session = Session::findOrFail($sessionId);

            return response()->json(
                [
                    'sessionId' => $session->id,
                    'iframeUrl' => config('app.url') . '/' . $lang . '/iframe/' . $domain->key . '/' . $session->id . '/' . $productModel,
                ]
            );
        }

        return response()->json(null, 204);
    }
}
