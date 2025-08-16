<?php

namespace App\Filament\Resources\TalentResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTalentRequest extends FormRequest
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
			'name' => 'required',
			'position' => 'required',
			'birthdate' => 'required|date',
			'summary' => 'required|string',
			'skills' => 'required',
			'educations' => 'required'
		];
    }
}
