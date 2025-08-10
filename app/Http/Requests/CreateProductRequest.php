<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            //categories table, column id.
            'product_gender' => 'required|string|in:men,women',
            'images' => 'required|array',
            'images.*.image_url' => 'required|string',
            'images.*.is_thumbnail' => 'required|boolean',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string|in:S,M,L,XL,XXL',
            'sizes.*.stock' => 'required|integer|min:0',
        ];
    }
}
