<?php

namespace App\Filament\Resources\CandidateResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateRequest extends FormRequest
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
			'talent_id' => 'required',
			'job_opening_id' => 'required',
			'status' => 'required',
            'screening' => 'nullable|json',
			'regist_at' => 'nullable',
			'interview_schedule' => 'nullable',
			'notified_at' => 'nullable'
		];
    }
}
