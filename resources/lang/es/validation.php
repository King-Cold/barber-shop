<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    // Custom message for password min specifically (overrides generic)
    'password' => [
        'min' => 'El campo contraseña debe tener al menos :min caracteres.',
    ],
    // other validation messages ...
    'confirmed' => 'El campo :attribute debe coincidir con su confirmación.',
    'password.confirmed' => 'La confirmación de la contraseña no coincide.',
];
