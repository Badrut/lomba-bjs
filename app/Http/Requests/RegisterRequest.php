<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'string', Rule::in(['nasabah', 'admin_cs', 'teller'])],
            'nik' => ['required_if:role,nasabah', 'string', 'digits:16', 'unique:nasabahs,nik'],
            'nomor_telepon' => ['required_if:role,nasabah', 'string', 'max:20', 'unique:nasabahs,nomor_telepon'],
            'alamat_ktp' => ['required_if:role,nasabah', 'string', 'max:500'],
            'jenis_kelamin' => ['required_if:role,nasabah', Rule::in(['Laki-laki', 'Perempuan'])],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nik.required_if' => 'NIK wajib diisi untuk role nasabah.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'nomor_telepon.required_if' => 'Nomor telepon wajib diisi untuk role nasabah.',
            'nomor_telepon.unique' => 'Nomor telepon ini sudah terdaftar.',
            'alamat_ktp.required_if' => 'Alamat KTP wajib diisi untuk role nasabah.',
            'jenis_kelamin.required_if' => 'Jenis kelamin wajib diisi untuk role nasabah.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validasi gagal.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
