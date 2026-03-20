<?php

namespace App\Http\Requests\RealEstate\FloorplanRoom;

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
            'floorplan_id' => 'required|exists:floorplans,id',
            'room_name' => 'required|max:150',
            'area_sqm' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'floorplan_id.required' => 'Bạn chưa chọn mặt bằng tầng.',
            'floorplan_id.exists' => 'Mặt bằng không hợp lệ.',
            'room_name.required' => 'Bạn chưa nhập tên phòng.',
            'area_sqm.required' => 'Bạn chưa nhập diện tích phòng.',
            'area_sqm.numeric' => 'Diện tích phải là dạng số.',
        ];
    }
}
