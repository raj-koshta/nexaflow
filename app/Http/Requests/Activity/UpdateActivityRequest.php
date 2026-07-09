<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
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
            'client_id' => 'sometimes|nullable|required_without:lead_id|exists:clients,id',
            'lead_id' => 'sometimes|nullable|required_without:client_id|exists:leads,id',
            'type' => 'sometimes|required|string|max:100',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'activity_date' => 'sometimes|required|date',
        ];
    }
}
