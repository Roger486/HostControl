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
        'create' => 'Reservation created.',
        'update' => 'Reservation updated.',
        'cancel' => 'Reservation cancelled.',
        'check_in' => 'Checked in to reservation.',
        'confirm' => 'Checked out from reservation.',
        'to_pending' => 'Reservation status set up to pending.',
        'default' => 'Action performed: :action',
    ]
];
