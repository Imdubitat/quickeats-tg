<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class validaCNPJ implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove caracteres não numéricos
        $cnpj = preg_replace('/\D/', '', $value);

        // Verifica se o CNPJ tem 14 dígitos
        if (strlen($cnpj) != 14) {
            $fail('CNPJ inválido.');
            return;
        }

        // Verifica se todos os dígitos são iguais (ex.: "11111111111111")
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $fail('CNPJ inválido.');
            return;
        }

        // Cálculo do primeiro dígito verificador
        $soma = 0;
        $multiplicadores = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $multiplicadores[$i];
        }

        $resto = $soma % 11;
        $primeiroDigitoVerificador = $resto < 2 ? 0 : 11 - $resto;

        // Cálculo do segundo dígito verificador
        $soma = 0;
        $multiplicadores = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $multiplicadores[$i];
        }

        $resto = $soma % 11;
        $segundoDigitoVerificador = $resto < 2 ? 0 : 11 - $resto;

        // Verifica os dígitos verificadores
        if ($primeiroDigitoVerificador != $cnpj[12] || $segundoDigitoVerificador != $cnpj[13]) {
            $fail('CNPJ inválido.');
        }
    }
}
