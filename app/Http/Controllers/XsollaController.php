<?php

namespace App\Http\Controllers;

use App\Services\Xsolla\XsollaService;
use GuzzleHttp\Exception\GuzzleException;
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
     * @return mixed
     * @throws GuzzleException
     * @throws JsonException
     */
    final public function getProducts(): mixed
    {
        return $this->xsollaService->getProducts();
    }

    /**
     * @return string
     * @throws GuzzleException
     * @throws JsonException
     */
    final public function createOrder(): string
    {
        return $this->xsollaService->createOrder();
    }
}

