<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FundAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $statusOnly = in_array($this->input('t_type'), ['Failed', 'Pending', 'Error'], true);

        $rules = [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:balance,crypto_btc,crypto_eth,crypto_usdt',
            't_type' => 'required|in:Credit,Debit,Failed,Pending,Error',
            'scope' => 'required|string',
            'Description' => 'nullable|string|max:500',
            'date' => 'nullable|date|before_or_equal:now',
            'notifymailuser' => 'required|in:0,1',
            'admin_note' => 'nullable|string|max:1000',
            'history_direction' => 'nullable|in:Credit,Debit',
            'sender_name' => 'nullable|string|max:255',
            'sender_bank' => 'nullable|string|max:255',
            'sender_account_number' => 'nullable|string|max:100',
            'sender_bank_address' => 'nullable|string|max:500',
            'reference_number' => 'nullable|string|max:100',
            'crypto_wallet_address' => 'nullable|string|max:255',
            'crypto_txn_hash' => 'nullable|string|max:255',
        ];

        // Real credit/debit require party or crypto details
        if (in_array($this->input('t_type'), ['Credit', 'Debit'], true)) {
            $isCrypto = str_starts_with($this->input('type', ''), 'crypto_');

            if ($isCrypto) {
                $rules['crypto_wallet_address'] = 'required|string|max:255';
                $rules['crypto_txn_hash'] = 'required|string|max:255';
            } else {
                $rules['sender_name'] = 'required|string|max:255';
                $rules['sender_bank'] = 'required|string|max:255';
                $rules['sender_account_number'] = 'required|string|max:100';
                $rules['sender_bank_address'] = 'required|string|max:500';
                $rules['reference_number'] = 'required|string|max:100|unique:withdrawals,reference_number';
            }
        }

        if ($statusOnly) {
            $rules['history_direction'] = 'required|in:Credit,Debit';
            if ($this->filled('reference_number')) {
                $rules['reference_number'] = 'nullable|string|max:100|unique:withdrawals,reference_number';
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'sender_name.required' => 'Party name is required (sender for credits, recipient for debits).',
            'sender_bank.required' => 'Party bank name is required.',
            'sender_account_number.required' => 'Party account number is required.',
            'sender_bank_address.required' => 'Party bank address is required.',
            'reference_number.required' => 'Bank reference number is required.',
            'reference_number.unique' => 'This reference number has already been used. Please check for duplicate entries.',
            'crypto_wallet_address.required' => 'Wallet address is required for crypto transactions.',
            'crypto_txn_hash.required' => 'Transaction hash (TXN ID) is required for crypto transactions.',
            'date.before_or_equal' => 'Transaction date cannot be in the future.',
            'amount.min' => 'Amount must be at least 0.01',
            'history_direction.required' => 'Please choose whether this shows as Credit or Debit in history.',
        ];
    }
}
