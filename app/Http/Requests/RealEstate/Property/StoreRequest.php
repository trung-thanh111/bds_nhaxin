<?php

namespace App\Http\Requests\RealEstate\Property;

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
            'title' => 'required|max:255',
            'slug' => 'required|unique:properties,slug',
            'price' => 'required|numeric',
            'area_sqm' => 'required|numeric',
            'address' => 'required',
            'district' => 'required',
            'city' => 'required',
            'image' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Bạn chưa nhập tiêu đề bất động sản.',
            'slug.required' => 'Bạn chưa nhập đường dẫn (slug).',
            'slug.unique' => 'Đường dẫn đã tồn tại, vui lòng chọn tên khác.',
            'price.required' => 'Bạn chưa nhập giá.',
            'price.numeric' => 'Giá phải là định dạng số.',
            'area_sqm.required' => 'Bạn chưa nhập diện tích.',
            'address.required' => 'Bạn chưa nhập địa chỉ.',
            'district.required' => 'Bạn chưa nhập quận/huyện.',
            'city.required' => 'Bạn chưa nhập thành phố.',
            'image.required' => 'Bạn chưa chọn ảnh đại diện.',
        ];
    }
}
