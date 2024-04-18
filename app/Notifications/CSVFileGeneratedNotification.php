<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CSVFileGeneratedNotification extends Notification
{
    use Queueable;

    protected $csvFileName;

    /**
     * Create a new notification instance.
     *
     * @param string $csvFileName
     * @return void
     */
    public function __construct($csvFileName)
    {
        $this->csvFileName = $csvFileName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'CSV File Generated',
            'message' => 'To download: <a href="'.route('download_csv', $this->csvFileName).'">Click Here</a>',
            'link' => route('download_csv', $this->csvFileName),
        ];
    }
}
