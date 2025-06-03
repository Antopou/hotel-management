<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Set to true if you handle authorization via middleware/gates elsewhere
        // Or implement specific authorization logic here (e.g., auth()->user()->can('create rooms'))
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100|unique:rooms,name', // Added unique rule
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
            'name.unique' => 'A room with this name already exists.',
            'room_type_code.exists' => 'The selected room type is invalid.',
        ];
    }
}