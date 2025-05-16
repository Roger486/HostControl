<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Reservation Log Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for logging reservation-related
    | actions within the system. Each line corresponds to a specific action
    | performed on a reservation. These logs help track changes and user
    | activities. The "default" line is used when an undefined action type
    | is encountered and dynamically inserts the action performed.
    |
    */

    'log_messages' => [
        'create' => 'Reserva creada.',
        'update' => 'Reserva actualizada.',
        'cancel' => 'Reserva cancelada.',
        'check_in' => 'Check-in realizado en la reserva.',
        'confirm' => 'Check-out realizado de la reserva.',
        'to_pending' => 'Reserva cambiada a estado pendiente.',
        'default' => 'Acción realizada: :action',
    ]
];
