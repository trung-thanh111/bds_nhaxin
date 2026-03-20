<?php

namespace App\Http\Requests\RealEstate\ContactRequest;

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
            'project_id' => 'required|exists:projects,id',
            'full_name' => 'required|max:150',
            'email' => 'required|email',
            'phone' => 'required',
            'subject' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Bạn chưa chọn dự án khách quan tâm.',
            'project_id.exists' => 'Dự án không hợp lệ.',
            'full_name.required' => 'Bạn chưa nhập họ tên khách hàng.',
            'email.required' => 'Bạn chưa nhập email khách hàng.',
            'email.email' => 'Email không đúng định dạng.',
            'phone.required' => 'Bạn chưa nhập số điện thoại khách hàng.',
        ];
    }
}
