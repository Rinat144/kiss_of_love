<?php

namespace App\Services\Xsolla;

class XsollaConfig
{
    public const PROJECT_ID = 216821;
    public const MERCHANT_ID = 401720;
    public const NAME_KEY_PROJECT = 'product_by_chat_4';

    /**
     * @return string
     */
    public static function getItemsUrl(): string
    {
        return 'https://store.xsolla.com/api/v2/project/' . self::PROJECT_ID . '/items/virtual_items';
    }

    /**
     * @return string
     */
    public static function getAuthUrl(): string
    {
        return 'https://api.xsolla.com/merchant/v2/merchants/' . self::MERCHANT_ID . '/token';
    }

    /**
     * @return string
     */
    public static function getCreateOrderUrl(): string
    {
        return 'https://store.xsolla.com/api/v2/project/'.self::PROJECT_ID.'/payment/item/' . self::NAME_KEY_PROJECT;
    }

    /**
     * @return string
     */
    public static function buyProduct(): string
    {
        return 'https://sandbox-secure.xsolla.com/paystation4/?token=';
    }
}
