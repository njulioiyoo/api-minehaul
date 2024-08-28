<?php

namespace App\Http\Requests\System\Menu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMenuRequest extends FormRequest
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
        $menuId = $this->route('menus');

        return [
            'name' => [
                'required',
                'string',
                'regex:/^[\p{L}0-9 ]+$/u',
                Rule::unique('menus', 'name')->ignore($menuId),
            ],
            'icon' => ['nullable', 'string'],
            'url' => ['nullable', 'string', 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
            'position' => ['required', 'integer'],
            'roles' => ['nullable', 'array'],
        ];
    }

    /**
     * Get the role ID from the request body.
     *
     * @return string|null
     */
    public function getRoleId(): ?string
    {
        return $this->input('data.id');
    }
}
