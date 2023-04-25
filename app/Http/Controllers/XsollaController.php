<?php

namespace App\Http\Controllers;

use App\Services\Xsolla\Exceptions\XsollaApiException;
use App\Services\Xsolla\Requests\XsollaCreateOrderRequest;
use App\Services\Xsolla\XsollaService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use JsonException;

class XsollaController extends Controller
{
    /**
     * @param XsollaService $xsollaService
     */
    public function __construct(
        public XsollaService $xsollaService
    ) {
    }

    /**
     * @param XsollaCreateOrderRequest $createOrderRequest
     * @return string
     * @throws GuzzleException
     * @throws JsonException
     */
    final public function createOrder(XsollaCreateOrderRequest $createOrderRequest): string
    {
        return $this->xsollaService->createOrder($createOrderRequest->post('xsolla_product_name'));
    }

    /**
     * @param Request $request
     * @return void
     * @throws JsonException
     * @throws XsollaApiException
     */
    final public function callback(Request $request):void
    {
        $this->xsollaService->callback($request);
    }
}

