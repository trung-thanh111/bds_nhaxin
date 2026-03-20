<?php

namespace App\Http\Requests\RealEstate\Floorplan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'floor_number' => 'required|numeric',
            'floor_label' => 'required|max:50',
            'plan_image' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'property_id.required' => 'Bạn chưa chọn bất động sản.',
            'property_id.exists' => 'Bất động sản không hợp lệ.',
            'floor_number.required' => 'Bạn chưa nhập số tầng.',
            'floor_label.required' => 'Bạn chưa nhập nhãn tầng (với dụ: Tầng 1).',
            'plan_image.required' => 'Bạn chưa chọn ảnh mặt bằng.',
        ];
    }
}
