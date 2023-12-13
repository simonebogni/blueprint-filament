<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
            'orderNumber' => ['required', 'integer'],
            'deliveryAddress' => ['required', 'string', 'max:100'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
        ];
    }
}
