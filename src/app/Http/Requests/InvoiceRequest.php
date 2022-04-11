<?php

namespace Payment\System\App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'planId' => 'required',
            'paymentMethodId' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'error.required',
            'string' => 'error.invalid_format'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'error' => true,
            'message' => 'error.auth.unauthorized'
        ], 401));
    }
}
