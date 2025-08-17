<?php

namespace App\Filament\Resources\CompanyPropertyResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyPropertyRequest extends FormRequest
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
			'company_id' => 'required',
			'key' => 'required',
			'value' => 'required'
		];
    }
}
