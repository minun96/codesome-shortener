<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShortCodeRequest extends FormRequest
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
            'long_url' => 'required|url',
            'short_code' => 'nullable|string|alpha_num|size:7|unique:links,short_code', // dico che deve essere unico altrimenti potrebbero esserci due link uguali. Se non viene inserito lo calcoliamo noi randomicamente
        ];
    }
}
