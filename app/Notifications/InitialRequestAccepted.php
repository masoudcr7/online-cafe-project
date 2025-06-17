<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\InitialRequest;

class InitialRequestAccepted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public InitialRequest $initialRequest)
    {
        // با استفاده از PHP 8 Property Promotion، این بخش ساده‌تر می‌شود.
    }

/**
 * Get the notification's delivery channels.
 */
public function via(object $notifiable): array
{
    return ['mail']; // ارسال از طریق ایمیل
}

/**
 * Get the mail representation of the notification.
 */
public function toMail(object $notifiable): MailMessage
{
    $serviceName = $this->initialRequest->service->name;
    $dashboardUrl = route('dashboard'); // لینک به داشبورد کاربر

    return (new MailMessage)
        ->subject('درخواست شما برای خدمت "' . $serviceName . '" قبول شد')
        ->greeting('سلام ' . $notifiable->name . '!')
        ->line('خبر خوب! درخواست شما برای خدمت "' . $serviceName . '" با موفقیت قبول شد.')
        ->line('پروژه جدیدی برای شما ایجاد شده است و می‌توانید مراحل بعدی را در پنل کاربری خود پیگیری کنید.')
        ->action('رفتن به پنل کاربری', $dashboardUrl)
        ->line('با تشکر از اعتماد شما!');
}

/**
 * Get the array representation of the notification.
 * (برای ذخیره در دیتابیس یا ارسال به کانال‌های دیگر)
 */
public function toArray(object $notifiable): array
{
    return [
        'request_id' => $this->initialRequest->id,
        'message' => 'درخواست شما برای خدمت ' . $this->initialRequest->service->name . ' قبول شد.',
    ];
}
}
