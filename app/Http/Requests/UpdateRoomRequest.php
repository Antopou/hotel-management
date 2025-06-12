<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Set to true if you handle authorization elsewhere (middleware/gates)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $roomId = $this->route('room')->id; // Assuming route model binding with 'room'

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

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Another room with this name already exists.',
            'room_type_code.exists' => 'The selected room type is invalid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('status')) {
            $this->merge([
                'status' => strtolower($this->input('status'))
            ]);
        }
    }
}
