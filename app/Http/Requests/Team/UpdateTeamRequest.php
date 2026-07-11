<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teamId = $this->route('team')->id ?? $this->route('team');
        
        return [
            'name' => ['required', 'string', 'max:255', 'unique:teams,name,' . $teamId],
            'description' => ['nullable', 'string'],
        ];
    }
}
