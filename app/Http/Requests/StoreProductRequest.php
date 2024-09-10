<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'code' => 'required|string|min:5|max:100|unique:products,code',
            'name' => 'required|string|min:3|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|min:3|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'stock' => 'required|integer|min:1',
        ];
    }
}
