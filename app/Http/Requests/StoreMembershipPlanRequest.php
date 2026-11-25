<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMembershipPlanRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
            ],

            'duration_value' => [
                'required',
                'integer',
                'min:1',
            ],

            'duration_unit' => [
                'required',
                Rule::in(['days', 'months', 'years']),
            ],

            'color_code' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the membership plan name.',
            'price.required' => 'Please enter the membership plan price.',
            'price.numeric' => 'The price must be a valid number.',
            'duration_value.required' => 'Please enter the duration.',
            'duration_unit.in' => 'Please select a valid duration unit.',
            'color_code.regex' => 'Please select a valid HEX color code.',
            'image_path.image' => 'The uploaded file must be an image.',
            'image_path.max' => 'The image size cannot exceed 2MB.',
        ];
    }
}
