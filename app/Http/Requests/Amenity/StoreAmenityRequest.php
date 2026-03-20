<?php

namespace App\Http\Requests\Amenity;

use Illuminate\Foundation\Http\FormRequest;

class StoreAmenityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'canonical' => 'required|unique:routers,canonical',
            'code' => 'nullable|unique:amenities,code',
            'amenity_catalogue_id' => 'required|gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào ô tiêu đề.',
            'canonical.required' => 'Bạn chưa nhập vào ô đường dẫn',
            'canonical.unique' => 'Đường dẫn đã tồn tại, Hãy chọn đường dẫn khác',
            'code.unique' => 'Mã tiện ích đã tồn tại.',
            'amenity_catalogue_id.required' => 'Bạn chưa chọn nhóm tiện ích.',
            'amenity_catalogue_id.gt' => 'Bạn chưa chọn nhóm tiện ích.',
        ];
    }
}
