<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'title'=>'required|min:3|string',
            'price'=>'required|numeric',
            'description'=>'nullable|string',
            'discountpercentage'=>'nullable|numeric',
            'rating'=>'nullable|numeric',
            'quantity'=>'required|integer',
            'brand'=>'required|string',
            'category_id'=>'required|exists:categories,id',
            'image'=>'image|mimes:png,jpg,jpeg,gif|max:2048'
        ];
    }
}
