<?php

namespace App\Http\Requests\RealEstate\PropertyFacility;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|max:150',
            'icon' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'property_id.required' => 'Bạn chưa chọn bất động sản.',
            'property_id.exists' => 'Bất động sản không hợp lệ.',
            'name.required' => 'Bạn chưa nhập tên tiện ích.',
            'icon.required' => 'Bạn chưa chọn icon cho tiện ích.',
        ];
    }
}
