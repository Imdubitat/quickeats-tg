<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class validaCelular implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\(\d{2}\)\s?\d{4,5}-\d{4}$/', $value)) {
            $fail('O formato do telefone é inválido. Use (XX) XXXX-XXXX ou (XX) XXXXX-XXXX.');
        }
    }

    public function message()
    {
        return 'O campo :attribute não é um celular com DDD válido.';
    }
}
