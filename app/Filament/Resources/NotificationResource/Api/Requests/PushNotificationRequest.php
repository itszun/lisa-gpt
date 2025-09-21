<?php

namespace App\Filament\Resources\NotificationResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PushNotificationRequest extends FormRequest
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
			'chat_user_id' => 'required|string',
			'subject' => 'required|string',
			'body' => 'required|string',
		];
    }
}
