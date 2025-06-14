<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
class BookRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'number_book' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer',
            'slug' => Str::slug($this->title, '-'),
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif'
        ];
    }
}