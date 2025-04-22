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
        'created' => 'Reservation created.',
        'updated' => 'Reservation updated.',
        'cancelled' => 'Reservation cancelled.',
        'checked_in' => 'Checked in to reservation.',
        'checked_out' => 'Checked out from reservation.',
        'default' => 'Action performed: :action',
    ]
];
