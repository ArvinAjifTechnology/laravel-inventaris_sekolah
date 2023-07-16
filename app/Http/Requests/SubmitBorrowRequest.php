<?php

namespace App\Http\Requests;

use App\Rules\SufficientQuantityRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitBorrowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'borrow_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['required', 'date', 'after:borrow_date'],
            'borrow_quantity' => ['required', 'integer', new SufficientQuantityRule],
        ];
    }
}
