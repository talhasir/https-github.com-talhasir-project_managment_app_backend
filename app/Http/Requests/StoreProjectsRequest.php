<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
{
    $this->merge([
        'created_by' => $this->user()->id,
        'updated_by' => $this->user()->id, 
    ]);
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required|string|max:100',
            'due_date' => 'nullable|date',
            'created_by' => 'required',
            'updated_by' => 'required'
            // 'image_path' => 'nullable|date',
        ];
    }
}
