<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'purchase' => ['required', 'array'],
            'purchase.purchase_id' => ['required', 'exists:purchases,id'],
            'purchase.supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase.total_items' => ['required', 'integer', 'min:1'],
            'purchase.total_price' => ['required', 'decimal:0,2'],
            'purchase.discount' => ['required', 'numeric', 'min:0'],
            'purchase.paid' => ['required', 'decimal:0,2'],

            'purchase_detail' => ['required', 'array'],
            'purchase_detail.*.product_id' => ['required', 'exists:products,id'],
            'purchase_detail.*.purchase_id' => ['required', 'exists:purchases,id'],
            'purchase_detail.*.code' => ['required', 'string', 'max:255'],
            'purchase_detail.*.name' => ['required', 'string', 'max:255'],
            'purchase_detail.*.purchase_price' => ['required', 'decimal:0,2'],
            'purchase_detail.*.quantity' => ['required', 'integer', 'min:1'],
            'purchase_detail.*.subtotal' => ['required', 'decimal:0,2'],
        ];
    }
}
