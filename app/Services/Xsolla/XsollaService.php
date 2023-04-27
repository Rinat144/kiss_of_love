<?php

namespace App\Services\Xsolla;

use App\Models\Product;
use App\Services\Payment\Enum\PaymentStatusEnum;
use App\Services\Payment\Repositories\PaymentRepository;
use App\Services\Product\Repositories\ProductRepository;
use App\Services\Transaction\Repositories\TransactionRepository;
use App\Services\User\Repositories\UserRepository;
use App\Services\User\UserService;
use App\Services\Xsolla\Enum\ExceptionEnum;
use App\Services\Xsolla\Exceptions\XsollaApiException;
use App\Support\LogChannelEnum;
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
     * @param UserService $userService
     * @param Client $client
     */
    public function __construct(
        public PaymentRepository $paymentRepository,
        public ProductRepository $productRepository,
        public TransactionRepository $transactionRepository,
        public UserRepository $userRepository,
        public UserService $userService,
        private Client $client,
    ) {
        $this->client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
        ]);
    }

    /**
     * @param string $xsollaProductName
     * @return string
     * @throws GuzzleException
     * @throws JsonException
     */
    final public function createOrder(string $xsollaProductName): string
    {
        $data = [
            "sandbox" => config('product.xsolla_sandbox'),
        ];

        $response = $this->client->post(
            XsollaConfig::getCreateOrderUrl($xsollaProductName),
            [
                'Bearer Token' => $this->getAuthToken(),
                'json' => $data,
            ]
        );

        $responseData = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

        $authUser = Auth::id();
        $product = $this->productRepository->getProduct($xsollaProductName);
        assert($product instanceof Product);

        $this->paymentRepository->store($responseData->order_id, $product, $authUser);

        Log::channel(LogChannelEnum::XSOLLA->value)->info('createOrder', [$response, $data, $responseData]);

        return XsollaConfig::buyProduct() . $responseData->token;
    }

    /**
     * @param Request $request
     * @return void
     * @throws JsonException
     * @throws XsollaApiException
     */
    final public function callback(Request $request): void
    {
        $authorizationKeySignature = $request->header('authorization');
        $authorizationKey = str_ireplace("Signature ", "", $authorizationKeySignature);
        $orderId = $request->post('order')['id'];

        if (($request->post('notification_type') === self::NOTIFICATION_TYPE)) {
            $this->checkSignature($request, $authorizationKey);
            $payment = $this->paymentRepository->getPaymentByOrderId($orderId);

            if ($payment?->status === PaymentStatusEnum::CREATED) {
                DB::transaction(function () use ($payment) {
                    $this->paymentRepository->updateStatusByPayment($payment, PaymentStatusEnum::PAID_FOR);
                    $this->userService->userAddBalance(
                        $payment->user_id,
                        $payment->user->balance,
                        $payment->product->amount
                    );
                    $this->transactionRepository->storeInflowTransaction(
                        $payment->user_id,
                        $payment->product_id,
                        $payment->user->balance,
                        $payment->product->amount,
                    );
                });
            }
        } else {
            throw new XsollaApiException(ExceptionEnum::INVALID_SIGNATURE_KEY);
        }
    }

    /**
     * @param Request $request
     * @param string $authorizationKey
     * @return void
     * @throws JsonException
     * @throws XsollaApiException
     */
    private function checkSignature(Request $request, string $authorizationKey): void
    {
        if (sha1(
                json_encode($request->post(), JSON_THROW_ON_ERROR) . config('product.webhook_kiss_of_love')
            ) !== $authorizationKey) {
            throw new XsollaApiException(ExceptionEnum::INVALID_SIGNATURE_KEY);
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
                'project_id' => (int)config('product.project_id'),
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
                'auth' => [config('product.merchant_id'), config('product.api_key_kiss_of_love')],
                'json' => $data,
            ]
        );

        $arrayResponse = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        Log::channel(LogChannelEnum::XSOLLA->value)->info('getAuthToken', [$response, $data, $arrayResponse]);

        return $arrayResponse;
    }
}
