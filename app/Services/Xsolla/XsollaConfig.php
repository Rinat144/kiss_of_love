<?php

namespace App\Services\Xsolla;

class XsollaConfig
{
    /**
     * @return string
     */
    public static function getAuthUrl(): string
    {
        return 'https://api.xsolla.com/merchant/v2/merchants/' . config('product.merchant_id') . '/token';
    }

    /**
     * @param string $xsollaProductName
     * @return string
     */
    public static function getCreateOrderUrl(string $xsollaProductName): string
    {
        return 'https://store.xsolla.com/api/v2/project/' . config(
                'product.project_id'
            ) . '/payment/item/' . $xsollaProductName;
    }

    /**
     * @return string
     */
    public static function buyProduct(): string
    {
        return 'https://sandbox-secure.xsolla.com/paystation4/?token=';
    }
}
