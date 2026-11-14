<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InternalTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $user = Auth::user();

        return [
            'accountnumber' => [
                'required',
                'string',
                'exists:users,usernumber',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value === $user->usernumber) {
                        $fail('You cannot transfer funds to your own account.');
                    }
                },
            ],
            'amount' => 'required|numeric|min:0.01|max:' . ($user->account_bal ?? 0),
            'pin' => 'required|string|min:4|max:10',
            'Description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'accountnumber.required' => 'Recipient account number is required.',
            'accountnumber.exists' => 'The account number does not exist in our system.',
            'amount.required' => 'Transfer amount is required.',
            'amount.min' => 'Transfer amount must be at least 0.01.',
            'amount.max' => 'Insufficient balance for this transfer.',
            'pin.required' => 'Transaction PIN is required.',
            'pin.min' => 'Transaction PIN must be at least 4 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('amount')) {
            $this->merge([
                'to_deduct' => (float) $this->amount,
            ]);
        }
    }

    /**
     * Get the validated data with computed fields.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if ($key === null) {
            $validated['to_deduct'] = (float) $validated['amount'];
        }

        return $validated;
    }
}
