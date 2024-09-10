<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            Rule::unique('products')->ignore($this->route('id')),
            'name' => 'required|string|min:3|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'sometimes|nullable|string|min:3|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'stock' => 'required|integer|min:1',
        ];
    }
}
