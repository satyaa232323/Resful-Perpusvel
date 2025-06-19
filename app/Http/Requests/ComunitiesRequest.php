<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComunitiesRequest extends FormRequest
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
            'content' => 'required|string|max:255',
            'image' => 'nullable|string', // Optional image upload
            'video' => 'nullable|string', // Status can be either active or inactive
        ];
    }
}
