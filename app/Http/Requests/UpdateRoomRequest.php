<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $roomId = $this->route('room')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('rooms')->ignore($roomId),
            ],
            'room_type_code' => 'required|exists:room_types,room_type_code',
            'status' => 'required|string|in:available,occupied,cleaning,maintenance',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Another room with this name already exists.',
            'room_type_code.exists' => 'The selected room type is invalid.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('status')) {
            $this->merge([
                'status' => strtolower($this->input('status'))
            ]);
        }
    }
}
