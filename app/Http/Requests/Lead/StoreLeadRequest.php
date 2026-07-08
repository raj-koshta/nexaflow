<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Use Policies in the Controller
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
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:100',
            'status' => 'required|in:new,contacted,qualified,lost',
            'priority' => 'required|in:low,medium,high',
            'expected_value' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}
