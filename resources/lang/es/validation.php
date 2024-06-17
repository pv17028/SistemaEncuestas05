<?php

return [
    'unique' => 'El :attribute ya ha sido tomado.',
    'required' => 'El campo :attribute es requerido.',
    'image' => 'El campo :attribute debe ser una imagen.',
    'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'confirmed' => 'El campo :attribute no coincide con la confirmación.',
    'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'attributes' => [
        'user_id' => 'usuario',
        'reason' => 'motivo',
        'password' => 'contraseña',
        'email' => 'correoElectronico',
        'username' => 'usuario',
        // ...
    ],
    'custom' => [
        'email' => [
            'required' => 'Necesitamos conocer tu dirección de correo electrónico.',
        ],
    ],
    // otros mensajes de validación...
];