<?php

namespace App\Http\Requests\RealEstate;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectCatalogueRequest extends FormRequest
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
            'canonical' => 'required|unique:project_catalogue_language,canonical' . ($this->id ? ',' . $this->id . ',project_catalogue_id' : ''),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào tên nhóm dự án.',
            'canonical.required' => 'Bạn chưa nhập vào đường dẫn.',
            'canonical.unique' => 'Đường dẫn đã tồn tại, hãy chọn đường dẫn khác.',
        ];
    }
}
