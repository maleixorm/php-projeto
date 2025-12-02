<?php

function formatarData($data) {
    return implode('/', array_reverse(explode('-', $data)));
}

function formatarTelefone($telefone) {
    $ddd = substr($telefone, 0, 2);
    $parte1 = substr($telefone, 2, 5);
    $parte2 = substr($telefone, 7);
    return "($ddd) $parte1$parte2";
}