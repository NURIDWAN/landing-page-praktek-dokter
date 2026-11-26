<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'min:8', 'max:25'],
            'message' => ['required', 'string', 'min:10', 'max:1500'],
            'privacy_agreed' => ['accepted'],
            'website_url' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Mohon isi nama lengkap Anda.',
            'phone.required' => 'Mohon isi nomor HP atau WhatsApp yang dapat dihubungi.',
            'message.required' => 'Mohon tuliskan pesan atau pertanyaan singkat Anda.',
            'message.min' => 'Pesan minimal terdiri dari 10 karakter.',
            'privacy_agreed.accepted' => 'Anda wajib menyetujui Kebijakan Privasi sebelum mengirim pesan.',
            'website_url.max' => 'Permintaan tidak valid.',
        ];
    }
}
