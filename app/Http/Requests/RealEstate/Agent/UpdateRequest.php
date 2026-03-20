<?php

namespace App\Http\Requests\RealEstate\Agent;

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
            'full_name' => 'required|max:150',
            'phone' => 'required',
            'email' => 'required|email|unique:agents,email,' . $this->id,
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Bạn chưa nhập họ tên nhân viên.',
            'phone.required' => 'Bạn chưa nhập số điện thoại.',
            'email.required' => 'Bạn chưa nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
        ];
    }
}
