<?php

namespace App\Http\Requests\RealEstate\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'real_estate_id' => 'required|exists:real_estates,id',
            'album' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'real_estate_id.required' => 'Bạn chưa chọn bất động sản.',
            'real_estate_id.exists' => 'Bất động sản không hợp lệ.',
            'album.required' => 'Bạn chưa chọn ảnh nào cho bộ sưu tập.',
            'album.array' => 'Dữ liệu ảnh không hợp lệ.',
        ];
    }
}
