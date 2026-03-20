<?php

namespace App\Http\Requests\RealEstate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFloorplanRequest extends FormRequest
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
            'real_estate_id' => 'required|gt:0',
            'name' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'real_estate_id.gt' => 'Bạn chưa chọn bất động sản.',
            'name.required' => 'Bạn chưa nhập tên mặt bằng.',
        ];
    }
}
