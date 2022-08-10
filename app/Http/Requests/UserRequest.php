<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'active' => 'required|in:1,0',
            'role_id' => 'required|exists:roles,id',
        ];
    }
    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'string' => 'هذا الحقل يجب ان يكون حروف وارقام',
            'password.confirmed' => 'هذا الحقل يجب ان يكون مطابق لحقل التاكيد',
            'email.unique' => ' هذاالاميل مستخدم من قبل',
        ];
    }
}
