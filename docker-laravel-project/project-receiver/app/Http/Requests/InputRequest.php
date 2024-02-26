<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InputRequest extends FormRequest
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
            'log_uuid' => 'required|uuid',
            'ip' => 'required|ip',
            'agent' => 'required|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'log_uuid.required' => 'The log_uuid field is required.',
            'log_uuid.uuid' => 'The log_uuid must be a valid UUID.',
            'ip.required' => 'The IP field is required.',
            'ip.ip' => 'The IP must be a valid IP address.',
            'agent.required' => 'The agent field is required.',
            'agent.string' => 'The agent field must be a string.',
        ];
    }
}
