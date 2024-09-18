<?php

declare(strict_types=1);

namespace App\Http\Requests\Configuration\Driver;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
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
        $driverId = $this->getDriverId();

        return [
            'pit_id' => ['nullable', 'integer', 'exists:pits,id'],
            'display_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]+$/'],
            'name' => ['required', 'string', 'regex:/^[\p{L}0-9 ]+$/u'],
            'email' => [
                'nullable',
                'email',
                'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,6}$/',
                Rule::unique('drivers', 'email')->ignore($driverId),
            ],
            'phone_number' => ['nullable', 'string', 'regex:/^\d+$/'],
        ];
    }

    public function getDriverId(): ?int
    {
        return Driver::select('id', 'uid')->where('uid', $this->input('uid'))->firstOrFail()->id;
    }
}
