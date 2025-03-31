<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class validaCPF implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $value);

        // Verifica se o CPF tem 11 dígitos e não é uma sequência repetida (ex: 111.111.111-11)
        if (strlen($cpf) != 11 || preg_match("/^{$cpf[0]}{11}$/", $cpf)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Validação do primeiro dígito verificador
        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $cpf[$i++] * $s--);
        if ($cpf[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Validação do segundo dígito verificador
        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $cpf[$i++] * $s--);
        if ($cpf[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $fail('O CPF informado é inválido.');
        }
    }

    public function message()
    {
    	return 'CPF inválido.';
    }
}