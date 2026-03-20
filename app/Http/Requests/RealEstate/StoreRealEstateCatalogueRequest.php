<?php

namespace App\Http\Requests\RealEstate;

use Illuminate\Foundation\Http\FormRequest;

class StoreRealEstateCatalogueRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'canonical' => 'required|unique:real_estate_catalogue_language',
            'parent_id' => 'gt:-1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào ô tiêu đề.',
            'canonical.required' => 'Bạn chưa nhập vào ô đường dẫn.',
            'canonical.unique' => 'Đường dẫn đã tồn tại, hãy chọn đường dẫn khác',
            'parent_id.gt' => 'Bạn chưa chọn danh mục cha',
        ];
    }
}
