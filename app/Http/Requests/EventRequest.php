<?php

namespace App\Http\Requests;

use App\Enums\TicketType;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        $data = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'is_cancelled' => 'integer'
        ];
        foreach (TicketType::cases() as $ticketType) {
            $data['tickets.' . $ticketType->value] = 'required|numeric|min:0';
            $data['ticket_price.' . $ticketType->value] = 'required|numeric|min:0';
        }
        return $data;
    }

    public function messages()
    {
        $messages = [];
        foreach (TicketType::cases() as $ticketType) {
            $messages['tickets.' . $ticketType->value . '.required'] = $ticketType->label() . ' is required.';
            $messages['tickets.' . $ticketType->value . '.numeric'] = $ticketType->label() . ' must be a number.';
            $messages['tickets.' . $ticketType->value . '.min'] = $ticketType->label() . ' must be at least 0.';
            $messages['ticket_price.' . $ticketType->value . '.required'] = $ticketType->label() . ' price is required.';
            $messages['ticket_price.' . $ticketType->value . '.numeric'] = $ticketType->label() . ' price must be a number.';
            $messages['ticket_price.' . $ticketType->value . '.min'] = $ticketType->label() . ' price must be at least 0.';
        }
        return $messages;
    }
}
