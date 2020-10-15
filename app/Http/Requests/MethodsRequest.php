<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Rules\PyScript;
use Illuminate\Foundation\Http\FormRequest;

class MethodsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'method_name' => 'required|min:1|max:255',
			'python_script_path' => ['required', 'file', new PyScript],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
        	'method_name' => 'Method name is a required field and must be less than 255 characters.',
            'python_script_path' => 'File must be a valid .py file.',
        ];
    }
}
