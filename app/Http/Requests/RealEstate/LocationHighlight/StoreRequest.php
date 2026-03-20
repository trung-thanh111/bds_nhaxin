<?php

namespace App\Http\Requests\RealEstate\LocationHighlight;

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
            'category' => 'required',
            'distance_text' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'property_id.required' => 'Bạn chưa chọn bất động sản.',
            'property_id.exists' => 'Bất động sản không hợp lệ.',
            'name.required' => 'Bạn chưa nhập tên tiện ích lân cận.',
            'category.required' => 'Bạn chưa chọn danh mục (Trường học, Bệnh viện...).',
            'distance_text.required' => 'Bạn chưa nhập khoảng cách (Ví dụ: 500m, 5 phút lái xe).',
        ];
    }
}
