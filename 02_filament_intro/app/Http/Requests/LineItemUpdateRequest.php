<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineItemUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'productCode' => ['required', 'string'],
            'quantity' => ['required', 'integer'],
            'pricePerUnit' => ['required', 'numeric', 'between:-999999.99,999999.99'],
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ];
    }
}
