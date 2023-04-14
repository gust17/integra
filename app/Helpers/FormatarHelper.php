<?php


if (!function_exists('format_currency')) {
    function format_currency($value, $currencyCode = 'BRL')
    {
        $formatter = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);

        //dd($formatter);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        return $formatter->formatCurrency($value, $currencyCode);
    }
}


if (!function_exists('format_porcentagem')) {
    function format_porcentagem($value)
    {
        $valor = round($value);
        return $valor;
    }
}

if (!function_exists('str_insert')) {
    function str_insert($str, $insert, $pos)
    {
        return substr($str, 0, $pos) . $insert . substr($str, $pos);
    }
}

if (!function_exists('limpa_corrige_cpf')){
    function limpa_corrige_cpf($value){

        //dd($value);
        $cpf = preg_replace('/[^a-zA-Z0-9\s]/', '', $value);

        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        return $cpf;
    }
}
