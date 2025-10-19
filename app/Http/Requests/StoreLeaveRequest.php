<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
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
    public function rules()
{
        return [
        'nom' => ['required', 'string', 'max:255'],
        'prenom' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8'],
        'matricule' => ['nullable', 'string', 'max:255', 'unique:users'],
        'poste' => ['nullable', 'string', 'max:255'],
        'direction' => ['nullable', 'string', 'max:255'],
        'telephone' => ['nullable', 'string', 'max:20'],
        'date_embauche' => ['nullable', 'date'],
        'is_active' => ['required', 'boolean'],  // ou 'nullable|boolean' si optionnel
    ];

}
     public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $service = app(LeaveValidationService::class);
            try {
                $service->validateLeavePeriod($this->date('start_date'));
            } catch (\Exception $e) {
                $validator->errors()->add('period', $e->getMessage());
            }
        });
    }

}
