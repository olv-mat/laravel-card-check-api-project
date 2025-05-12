<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BINRequest extends FormRequest
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
            "bin" => ["required", "string", "min:6", "max:8"]
        ];
    }
}
