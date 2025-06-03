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
        return true; // Similar to StoreRoomRequest, handle via middleware/gates or here
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Get the room ID from the route parameters
        $roomId = $this->route('room')->id; // Assuming route model binding is used and param is 'room'

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('rooms')->ignore($roomId), // Ignore current room's name for unique check
            ],
            'room_type_code' => 'required|exists:room_types,room_type_code',
            'status' => 'required|string|in:Available,Occupied,Cleaning,Maintenance',
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
}