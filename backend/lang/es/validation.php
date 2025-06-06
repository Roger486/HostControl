<?php

declare(strict_types=1);

return [
    'accepted'               => 'El campo :attribute debe ser aceptado.',
    'accepted_if'            => 'El campo :attribute debe ser aceptado cuando :other sea :value.',
    'active_url'             => 'El campo :attribute debe ser una URL válida.',
    'after'                  => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'         => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                  => 'El campo :attribute sólo debe contener letras.',
    'alpha_dash'             => 'El campo :attribute sólo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num'              => 'El campo :attribute sólo debe contener letras y números.',
    'any_of'                 => 'El campo :attribute no es válido.',
    'array'                  => 'El campo :attribute debe ser un conjunto.',
    'ascii'                  => 'El campo :attribute solo debe contener caracteres alfanuméricos y símbolos de un solo byte.',
    'before'                 => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal'        => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'                => [
        'array'   => 'El campo :attribute tiene que tener entre :min - :max elementos.',
        'file'    => 'El campo :attribute debe pesar entre :min - :max kilobytes.',
        'numeric' => 'El campo :attribute tiene que estar entre :min - :max.',
        'string'  => 'El campo :attribute tiene que tener entre :min - :max caracteres.',
    ],
    'boolean'                => 'El campo :attribute debe tener un valor verdadero o falso.',
    'can'                    => 'El campo :attribute contiene un valor no autorizado.',
    'confirmed'              => 'La confirmación de :attribute no coincide.',
    'contains'               => 'Al campo :attribute le falta un valor obligatorio.',
    'current_password'       => 'La contraseña es incorrecta.',
    'date'                   => 'El campo :attribute debe ser una fecha válida.',
    'date_equals'            => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format'            => 'El campo :attribute debe coincidir con el formato :format.',
    'decimal'                => 'El campo :attribute debe tener :decimal cifras decimales.',
    'declined'               => 'El campo :attribute debe ser rechazado.',
    'declined_if'            => 'El campo :attribute debe ser rechazado cuando :other sea :value.',
    'different'              => 'El campo :attribute y :other deben ser diferentes.',
    'digits'                 => 'El campo :attribute debe tener :digits dígitos.',
    'digits_between'         => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'dimensions'             => 'El campo :attribute tiene dimensiones de imagen no válidas.',
    'distinct'               => 'El campo :attribute contiene un valor duplicado.',
    'doesnt_end_with'        => 'El campo :attribute no debe finalizar con uno de los siguientes: :values.',
    'doesnt_start_with'      => 'El campo :attribute no debe comenzar con uno de los siguientes: :values.',
    'email'                  => 'El campo :attribute no es un correo válido.',
    'ends_with'              => 'El campo :attribute debe finalizar con uno de los siguientes valores: :values',
    'enum'                   => 'El campo :attribute no está en la lista de valores permitidos.',
    'exists'                 => 'El campo :attribute no existe.',
    'extensions'             => 'El campo :attribute debe tener una de las siguientes extensiones: :values.',
    'file'                   => 'El campo :attribute debe ser un archivo.',
    'filled'                 => 'El campo :attribute es obligatorio.',
    'gt'                     => [
        'array'   => 'El campo :attribute debe tener más de :value elementos.',
        'file'    => 'El campo :attribute debe tener más de :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'string'  => 'El campo :attribute debe tener más de :value caracteres.',
    ],
    'gte'                    => [
        'array'   => 'El campo :attribute debe tener como mínimo :value elementos.',
        'file'    => 'El campo :attribute debe tener como mínimo :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser como mínimo :value.',
        'string'  => 'El campo :attribute debe tener como mínimo :value caracteres.',
    ],
    'hex_color'              => 'El campo :attribute debe tener un color hexadecimal válido.',
    'image'                  => 'El campo :attribute debe ser una imagen.',
    'in'                     => 'El campo :attribute no está en la lista de valores permitidos.',
    'in_array'               => 'El campo :attribute debe existir en :other.',
    'integer'                => 'El campo :attribute debe ser un número entero.',
    'ip'                     => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4'                   => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6'                   => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json'                   => 'El campo :attribute debe ser una cadena JSON válida.',
    'list'                   => 'El campo :attribute debe ser una lista.',
    'lowercase'              => 'El campo :attribute debe estar en minúscula.',
    'lt'                     => [
        'array'   => 'El campo :attribute debe tener menos de :value elementos.',
        'file'    => 'El campo :attribute debe tener menos de :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'string'  => 'El campo :attribute debe tener menos de :value caracteres.',
    ],
    'lte'                    => [
        'array'   => 'El campo :attribute debe tener como máximo :value elementos.',
        'file'    => 'El campo :attribute debe tener como máximo :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser como máximo :value.',
        'string'  => 'El campo :attribute debe tener como máximo :value caracteres.',
    ],
    'mac_address'            => 'El campo :attribute debe ser una dirección MAC válida.',
    'max'                    => [
        'array'   => 'El campo :attribute no debe tener más de :max elementos.',
        'file'    => 'El campo :attribute no debe ser mayor que :max kilobytes.',
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'string'  => 'El campo :attribute no debe ser mayor que :max caracteres.',
    ],
    'max_digits'             => 'El campo :attribute no debe tener más de :max dígitos.',
    'mimes'                  => 'El campo :attribute debe ser un archivo con formato: :values.',
    'mimetypes'              => 'El campo :attribute debe ser un archivo con formato: :values.',
    'min'                    => [
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
        'file'    => 'El tamaño de :attribute debe ser de al menos :min kilobytes.',
        'numeric' => 'El tamaño de :attribute debe ser de al menos :min.',
        'string'  => 'El campo :attribute debe contener al menos :min caracteres.',
    ],
    'min_digits'             => 'El campo :attribute debe tener al menos :min dígitos.',
    'missing'                => 'El campo :attribute no debe estar presente.',
    'missing_if'             => 'El campo :attribute no debe estar presente cuando :other sea :value.',
    'missing_unless'         => 'El campo :attribute no debe estar presente a menos que :other sea :value.',
    'missing_with'           => 'El campo :attribute no debe estar presente si alguno de los campos :values está presente.',
    'missing_with_all'       => 'El campo :attribute no debe estar presente cuando los campos :values estén presentes.',
    'multiple_of'            => 'El campo :attribute debe ser múltiplo de :value',
    'not_in'                 => 'El campo :attribute no debe estar en la lista.',
    'not_regex'              => 'El formato del campo :attribute no es válido.',
    'numeric'                => 'El campo :attribute debe ser numérico.',
    'password'               => [
        'letters'       => 'La :attribute debe contener al menos una letra.',
        'mixed'         => 'La :attribute debe contener al menos una letra mayúscula y una minúscula.',
        'numbers'       => 'La :attribute debe contener al menos un número.',
        'symbols'       => 'La :attribute debe contener al menos un símbolo.',
        'uncompromised' =>
            'La :attribute proporcionada se ha visto comprometida en una filtración de datos (data leak). Elija una :attribute diferente.',
    ],
    'present'                => 'El campo :attribute debe estar presente.',
    'present_if'             => 'El campo :attribute debe estar presente cuando :other es :value.',
    'present_unless'         => 'El campo :attribute debe estar presente a menos que :other sea :value.',
    'present_with'           => 'El campo :attribute debe estar presente cuando :values esté presente.',
    'present_with_all'       => 'El campo :attribute debe estar presente cuando :values estén presentes.',
    'prohibited'             => 'El campo :attribute está prohibido.',
    'prohibited_if'          => 'El campo :attribute está prohibido cuando :other es :value.',
    'prohibited_if_accepted' => 'El campo :attribute está prohibido cuando se acepta :other.',
    'prohibited_if_declined' => 'El campo :attribute está prohibido cuando se rechaza :other.',
    'prohibited_unless'      => 'El campo :attribute está prohibido a menos que :other sea :values.',
    'prohibits'              => 'El campo :attribute prohibe que :other esté presente.',
    'regex'                  => 'El formato del campo :attribute no es válido.',
    'required'               => 'El campo :attribute es obligatorio.',
    'required_array_keys'    => 'El campo :attribute debe contener entradas para: :values.',
    'required_if'            => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_if_accepted'   => 'El campo :attribute es obligatorio si :other es aceptado.',
    'required_if_declined'   => 'El campo :attribute es obligatorio si :other es rechazado.',
    'required_unless'        => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'          => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'      => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without'       => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all'   => 'El campo :attribute es obligatorio cuando ninguno de :values está presente.',
    'same'                   => 'Los campos :attribute y :other deben coincidir.',
    'size'                   => [
        'array'   => 'El campo :attribute debe contener :size elementos.',
        'file'    => 'El tamaño de :attribute debe ser :size kilobytes.',
        'numeric' => 'El tamaño de :attribute debe ser :size.',
        'string'  => 'El campo :attribute debe contener :size caracteres.',
    ],
    'starts_with'            => 'El campo :attribute debe comenzar con uno de los siguientes valores: :values',
    'string'                 => 'El campo :attribute debe ser una cadena de caracteres.',
    'timezone'               => 'El campo :attribute debe ser una zona horaria válida.',
    'ulid'                   => 'El campo :attribute debe ser un ULID válido.',
    'unique'                 => 'El campo :attribute ya ha sido registrado.',
    'uploaded'               => 'Subir :attribute ha fallado.',
    'uppercase'              => 'El campo :attribute debe estar en mayúscula.',
    'url'                    => 'El campo :attribute debe ser una URL válida.',
    'uuid'                   => 'El campo :attribute debe ser un UUID válido.',
    // Custom validation messages
    'custom' => [
        'companions' => [
            '*' => [
                'document_number' => [
                    'required_for_adults' =>
                        'El tipo y número de documento son obligatorios para los adultos.',
                    'not_repeated' =>
                        'Cada acompañante debe tener un número de documento único y distinto al del huésped.',
                    'both_or_none_for_minors' =>
                        'Para menores, el tipo y número de documento deben estar ambos presentes o ambos omitidos.',
                ],
            ],
        ],
        'search' => [
            'multiple_results' => 'Esta búsqueda devolvió varios resultados. Por favor, contacta con el administrador.',
            'no_results' => 'No se encontraron resultados para esta búsqueda.',
        ],
        'reservation' => [
            'not_free_by_date' => 'Este alojamiento ya está reservado entre las fechas indicadas.',
            'status_doesnt_allow_service_attachment' => 'No puedes añadir servicios a esta reserva en su estado actual: :status.'
        ],
        'service' => [
            'not_enough_slots' => 'No hay suficientes plazas disponibles para este servicio. Solo quedan :available.',
            'invalid_request_data' => 'Faltan datos o los datos de la solicitud son inválidos. Por favor, inténtalo de nuevo.',
            'available_until_after_scheduled_at' => 'La fecha de fin de disponibilidad debe ser anterior o igual a la fecha de inicio programada.',
            'ends_before_scheduled_at' => 'La fecha de finalización debe ser posterior a la fecha de inicio programada.',
            'available_until_after_ends_at' => 'La fecha de fin de disponibilidad debe ser anterior o igual a la fecha de fin del servicio.',
            'unavailable_due_to_available_until' => 'Este servicio ya no está disponible para su reserva.',
            'unavailable_due_to_scheduled_at' => 'Este servicio ya ha comenzado y no se puede reservar.',
            'unavailable_due_to_ended' => 'Este servicio ya ha finalizado.'
        ]
    ],
    'attributes' => [
        // Multiple models
        'comments' => 'comentarios',
        // Accommodation
        'min_capacity' => 'capacidad mínima',
        'max_capacity' => 'capacidad máxima',
        'check_in_date' => 'fecha de entrada',
        'check_out_date' => 'fecha de salida',
        'accommodation_code' => 'código del alojamiento',
        'section' => 'sección',
        'capacity' => 'capacidad',
        'price_per_day' => 'precio por día',
        'is_available' => 'está disponible',
        'type' => 'tipo',
        // Reservation
        'booked_by_id' => 'persona que realiza la reserva',
        'guest_id' => 'huésped',
        'accommodation_id' => 'alojamiento',
        'status' => 'estado',
        // companion specific fields on Reservation
        'companions' => 'acompañantes',
        'companions.*.document_type' => 'tipo de documento del acompañante',
        'companions.*.first_name' => 'nombre del acompañante',
        'companions.*.last_name_1' => 'primer apellido del acompañante',
        'companions.*.last_name_2' => 'segundo apellido del acompañante',
        'companions.*.birthdate' => 'fecha de nacimiento del acompañante',
        // Reservation logs
        'log_detail' => 'detalle del registro',
        // Service
        'service_id' => 'servicio',
        'name' => 'nombre',
        'description' => 'descripción',
        'price' => 'precio',
        'daily_price' => 'precio por día',
        'available_slots' => 'plazas disponibles',
        'available_until' => 'disponible hasta',
        'scheduled_at' => 'fecha de inicio',
        'ends_at' => 'fecha de finalización',
        // Service-Reservation attachment
        'amount' => 'cantidad',
        // User
        'first_name' => 'nombre',
        'last_name_1' => 'primer apellido',
        'last_name_2' => 'segundo apellido',
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'birthdate' => 'fecha de nacimiento',
        'address' => 'dirección',
        'document_type' => 'tipo de documento',
        'document_number' => 'número de documento',
        'phone' => 'teléfono',
        'role' => 'rol',
    ]
];
