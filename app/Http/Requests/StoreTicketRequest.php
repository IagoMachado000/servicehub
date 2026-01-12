<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'title'      => ['required', 'string', 'max:255'],
            'attachment' => ['nullable', 'file', 'mimes:json,txt', 'max:2048'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'project_id.required' => 'Selecione o projeto.',
            'title.required' => 'Insira o assunto do ticket.',
            'title.max' => 'Esse campo deve ter no máximo :max caracteres.',
            'attachment.mimes' => 'Apenas arquivos JSON ou TXT são permitidos.',
            'attachment.max' => 'O arquivo deve ter no máximo :max MB'
        ];
    }
}
