<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DomainRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url' => ['required', 'url'],
            'check_interval' => ['required', 'integer', 'min:1', 'max:1440'],
            'request_timeout' => ['required', 'integer', 'min:1', 'max:30'],
            'check_method' => ['required', 'string', 'in:GET,HEAD'],
        ];
    }
}
