<?php

namespace App\Services\Xsolla;

use App\Services\Payment\Repositories\PaymentRepository;
use App\Services\Product\Repositories\ProductRepository;
use App\Services\Transaction\Repositories\TransactionRepository;
use App\Services\User\Repositories\UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JsonException;

class XsollaService
{
    private const NOTIFICATION_TYPE = 'order_paid';

    /**
     * @param PaymentRepository $paymentRepository
     * @param ProductRepository $productRepository
     * @param TransactionRepository $transactionRepository
     * @param UserRepository $userRepository
     * @param Client $client
     */
    public function __construct(
        public PaymentRepository $paymentRepository,
        public ProductRepository $productRepository,
        public TransactionRepository $transactionRepository,
        public UserRepository $userRepository,
        private Client $client,
    ) {
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
                "user_id" => Auth::id(),
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

        $authUser = Auth::user();
        DB::transaction(function () use ($token, $authUser) {
            $productData = $this->productRepository->getProduct(XsollaConfig::NAME_KEY_PROJECT);  //вот здесь думаю нужно сделать, чтобы с фронта приходило название продукта
            $this->paymentRepository->store($token->order_id, $productData, $authUser->id);
        });

        return XsollaConfig::buyProduct() . $token->token;
    }

    /**
     * @param Request $request
     * @return void
     * @throws JsonException
     */
    final public function callback(Request $request): void
    {
        $authorizationKeySignature = $request->header('authorization');
        $authorizationKey = str_ireplace("Signature ", "", $authorizationKeySignature);
        $orderId = $request->post('order')['id'];
        $nameProduct = $request->post('items')[0]['sku'];
        $userId = $request->post('custom_parameters')['user_id'];

        if (($request->post('notification_type') === self::NOTIFICATION_TYPE) && sha1(
                json_encode($request->post(), JSON_THROW_ON_ERROR) . config('productkey.webhook_kiss_of_love')
            ) === $authorizationKey) {
            DB::transaction(function () use ($orderId, $nameProduct, $userId) {
                $userBalance = $this->userRepository->getValueBalance($userId);
                $this->paymentRepository->update($orderId);
                $productData = $this->productRepository->getProduct($nameProduct);
                $this->transactionRepository->storeOutlayTransaction(
                    $userId,
                    $productData->id,
                    $userBalance,
                    $productData->amount
                );
            });
        }
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
