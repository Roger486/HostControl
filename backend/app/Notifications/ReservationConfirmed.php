<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification sent to a user when their reservation is confirmed.
 */
class ReservationConfirmed extends Notification
{
    use Queueable;

    /**
     * The reservation instance.
     *
     * @var \App\Models\Reservation
     */
    protected $reservation;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }


    /**
     * Get the delivery channels for the notification.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array of data to store in the database notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        $statusKey = Reservation::STATUS_CONFIRMED;

        return [
            'type' => $statusKey,
            'title' => __("notifications.{$statusKey}.title"),
            'message' => __("notifications.{$statusKey}.message"),
            'reservation_id' => $this->reservation->id,
            'date' => now(),
        ];
    }


    /**
     * (Optional) Get the array representation of the notification.
     * Not used in this case, but required by the Notification system.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
