<?php

namespace App\Http\Requests;

use App\Rules\TwoWords;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method() === 'PUT') {
            $reservation = $this->route('reservation');
            return [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('reservations')->where('user_id', auth()->id())->ignore($reservation),
                ],
                'check_in' => 'required|date_format:Y-m-d|after_or_equal:today',
                'check_out' => 'required|date_format:Y-m-d|after:check_in',
            ];
        }
        return [
            'name' => ['required', 'string', 'unique:reservations', new TwoWords()],
            'check_in' => 'required|date_format:Y-m-d|after_or_equal:today',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
        ];
    }
}
