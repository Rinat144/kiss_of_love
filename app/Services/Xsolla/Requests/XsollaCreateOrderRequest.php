<?php

namespace App\Services\Xsolla\Requests;

use Illuminate\Foundation\Http\FormRequest;

class XsollaCreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    final public function rules(): array
    {
        return [
            'xsolla_product_name' => ['required', 'string'],
        ];
    }
}
