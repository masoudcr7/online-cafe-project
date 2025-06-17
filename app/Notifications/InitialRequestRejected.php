<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\InitialRequest;

class InitialRequestRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public InitialRequest $initialRequest)
    {
        //
    }

/**
 * Get the notification's delivery channels.
 */
public function via(object $notifiable): array
{
    return ['mail'];
}

/**
 * Get the mail representation of the notification.
 */
public function toMail(object $notifiable): MailMessage
{
    $serviceName = $this->initialRequest->service->name;
    $rejectionMessage = $this->initialRequest->admin_message;
    $newRequestUrl = route('initial-requests.create');

    return (new MailMessage)
        ->subject('درخواست شما برای خدمت "' . $serviceName . '" رد شد')
        ->greeting('سلام ' . $notifiable->name . '!')
        ->line('متاسفانه درخواست شما برای خدمت "' . $serviceName . '" توسط مدیر سیستم بررسی و رد شد.')
        ->line('**دلیل رد درخواست:**')
        ->line($rejectionMessage)
        ->line('در صورت تمایل، می‌توانید درخواست جدیدی ثبت کنید.')
        ->action('ثبت درخواست جدید', $newRequestUrl)
        ->line('با تشکر.');
}

/**
 * Get the array representation of the notification.
 */
public function toArray(object $notifiable): array
{
    return [
        'request_id' => $this->initialRequest->id,
        'message' => 'درخواست شما برای خدمت ' . $this->initialRequest->service->name . ' رد شد.',
    ];
}
}
