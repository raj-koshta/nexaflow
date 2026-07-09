<?php

namespace App\Http\Requests\FollowUp;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowUpRequest extends FormRequest
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
            'follow_date' => 'sometimes|required|date',
            'follow_time' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string',
            'status' => 'sometimes|required|string|in:Pending,Completed',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}
