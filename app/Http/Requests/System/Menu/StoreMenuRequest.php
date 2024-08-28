<?php

namespace App\Http\Requests\System\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
            'name' => ['required', 'string', 'regex:/^[A-Za-z0-9\s]+$/'],
            'icon' => ['nullable', 'string'],
            'url' => ['nullable', 'string', 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
            'position' => ['required', 'integer'],
            'roles' => ['nullable', 'array'],
        ];
    }
}
