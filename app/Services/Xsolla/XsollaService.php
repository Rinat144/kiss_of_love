<?php

namespace App\Services\Xsolla;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JsonException;

class XsollaService
{
    /**
     * @param Client $client
     */
    public function __construct(private Client $client)
    {
        $this->client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
        ]);
    }

    /**
     * @return mixed
     * @throws GuzzleException
     * @throws JsonException
     */
    final public function getProducts(): mixed
    {
        $response = $this->client->request('GET', XsollaConfig::getItemsUrl());

        return json_decode($response->getBody(), false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return string
     * @throws GuzzleException
     * @throws JsonException
     */
    final public function createOrder(): string
    {
        $data = [
            "sandbox" => true,
            "quantity" => 1,
            "settings" => [
                "ui" => [
                    "size" => "large",

                    "theme" => "default",
                    "version" => "desktop",
                    "desktop" => [
                        "header" => [
                            "is_visible" => true,
                            "visible_logo" => true,
                            "visible_name" => true,
                            "visible_purchase" => true,
                            "type" => "normal",
                            "close_button" => false,
                        ]
                    ],
                    "mobile" => [
                        "footer" => [
                            "is_visible" => true,
                        ],
                        "header" => [
                            "close_button" => false,
                        ]
                    ]
                ]
            ],
            "custom_parameters" => [
                "character_id" => "name",
            ]
        ];

        $response = $this->client->post(
            XsollaConfig::getCreateOrderUrl(),
            [
                'Bearer Token' => $this->getAuthToken(),
                'json' => $data,
            ]
        );

        $token = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        return XsollaConfig::buyProduct() . $token->token;
    }


    /**
     * @return mixed
     * @throws GuzzleException
     * @throws JsonException
     */
    private function getAuthToken(): mixed
    {
        $data = [
            'settings' => [
                'project_id' => XsollaConfig::PROJECT_ID,
                'currency' => 'RUB',
                'language' => 'ru',
                'ui' => [
                    'size' => 'medium'
                ]
            ],
            'user' => [
                'id' => ['value' => (string)Auth::id()],
                'name' => ['value' => Auth::user()->name],
            ]
        ];

        $response = $this->client->post(
            XsollaConfig::getAuthUrl(),
            [
                'auth' => [XsollaConfig::MERCHANT_ID, config('productkey.api_key_kiss_of_love')],
                'json' => $data,
            ]
        );

        $arrayResponse = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        Log::info('getAuthToken', [$response, $data, $arrayResponse]);

        return $arrayResponse;
    }
}
