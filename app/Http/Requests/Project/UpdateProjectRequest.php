<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id'); 
        return [
            'name' => 'required|string|max:255',
            'canonical' => 'required|unique:routers,canonical, '.$id.',module_id',
            'project_catalogue_id' => 'required|gt:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập tên dự án',
            'name.string' => 'Tên dự án phải là dạng ký tự',
            'name.max' => 'Tên dự án không được vượt quá 255 ký tự',
            'canonical.required' => 'Bạn chưa nhập vào ô đường dẫn',
            'canonical.unique' => 'Đường dẫn đã tồn tại, Hãy chọn đường dẫn khác',
            'project_catalogue_id.gt' => 'Bạn phải chọn cho dự án một nhóm cụ thể.',
        ];
    }
}