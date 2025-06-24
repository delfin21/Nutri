<?php

return [

    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'confirmed' => 'The :attribute confirmation does not match.',

    // ✅ Add your custom regex message here
    'custom' => [
        'password' => [
            'regex' => 'Password must be at least 8 characters and include an uppercase letter, lowercase letter, number, and special character.',
        ],
    ],

];
