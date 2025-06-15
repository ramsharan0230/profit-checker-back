<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReportRequest extends FormRequest
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
            'reportType' => ['required', Rule::in(['pdf', 'csv'])],

            'selectedItems' => ['required', 'array', 'min:1'],
            'selectedItems.*.selectedProduct' => ['required', 'string'],
            'selectedItems.*.quantity' => ['required', 'integer', 'min:1'],
            'selectedItems.*.cost' => ['required', 'numeric', 'min:0'],
            'selectedItems.*.sell' => ['required', 'numeric', 'min:0'],

            'laborHours' => ['required', 'numeric', 'min:0'],
            'laborCost' => ['required', 'numeric', 'min:0'],
            'fixedOverheads' => ['required', 'numeric', 'min:0'],
            'targetMargin' => ['required', 'numeric', 'min:0'],

            'allProducts' => ['sometimes', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'reportType.required' => 'Report type is required.',
            'reportType.in' => 'Report type must be either pdf or csv.',

            'selectedItems.required' => 'At least one selected item is required.',
            'selectedItems.array' => 'Selected items must be an array.',

            'selectedItems.*.selectedProduct.required' => 'Each item must have a selected product.',
            'selectedItems.*.quantity.required' => 'Quantity is required for each item.',
            'selectedItems.*.quantity.integer' => 'Quantity must be an integer.',
            'selectedItems.*.quantity.min' => 'Quantity must be at least 1.',
            'selectedItems.*.cost.required' => 'Cost is required for each item.',
            'selectedItems.*.cost.numeric' => 'Cost must be a number.',
            'selectedItems.*.cost.min' => 'Cost cannot be negative.',
            'selectedItems.*.sell.required' => 'Sell price is required for each item.',
            'selectedItems.*.sell.numeric' => 'Sell price must be a number.',
            'selectedItems.*.sell.min' => 'Sell price cannot be negative.',

            'laborHours.required' => 'Labor hours is required.',
            'laborHours.numeric' => 'Labor hours must be a number.',
            'laborHours.min' => 'Labor hours cannot be negative.',

            'laborCost.required' => 'Labor cost is required.',
            'laborCost.numeric' => 'Labor cost must be a number.',
            'laborCost.min' => 'Labor cost cannot be negative.',

            'fixedOverheads.required' => 'Fixed overheads is required.',
            'fixedOverheads.numeric' => 'Fixed overheads must be a number.',
            'fixedOverheads.min' => 'Fixed overheads cannot be negative.',

            'targetMargin.required' => 'Target margin is required.',
            'targetMargin.numeric' => 'Target margin must be a number.',
            'targetMargin.min' => 'Target margin cannot be negative.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json([
                'data' => $errors,
                'message' => 'Validation failed',
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
