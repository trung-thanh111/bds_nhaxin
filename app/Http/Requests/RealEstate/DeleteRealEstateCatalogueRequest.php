<?php

namespace App\Http\Requests\RealEstate;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\RealEstateCatalogue;

class DeleteRealEstateCatalogueRequest extends FormRequest
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
            'id' => [
                function ($attribute, $value, $fail) {
                    $id = $this->route('id');
                    $realEstateCatalogue = RealEstateCatalogue::find($id);
                    if ($realEstateCatalogue->rgt - $realEstateCatalogue->lft !== 1) {
                        $fail('Không thể xóa danh mục này vì vẫn còn danh mục con');
                    }
                },
            ],
        ];
    }
}
