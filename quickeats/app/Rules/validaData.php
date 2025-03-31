<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use DateTime;

class validaData implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verifica se o valor está em branco ou nulo
        if (empty($value)) {
            $fail('A data de nascimento é obrigatória.');
            return;
        }

        // Obtém a data de hoje
        $hoje = new DateTime();

        // Converte o valor de entrada para um objeto DateTime
        try {
            $nascimento = new DateTime($value);
        } catch (\Exception $e) {
            $fail('Data de nascimento inválida.');
            return;
        }

        // Calcula a idade
        $idade = $hoje->diff($nascimento)->y;

        // Verifica se a idade está entre 13 e 125 anos
        if ($idade < 13 || $idade > 125) {
            $fail('A idade deve estar entre 13 e 125 anos.');
        }
    }

    public function message()
    {
        return 'A idade deve ser entre 13 e 125 anos.';
    }
}
