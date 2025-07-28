<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Requisition;

class RequisitionSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $requisition;

    public function __construct(Requisition $requisition)
    {
        $this->requisition = $requisition;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pending Approval: Requisition ' . $this->requisition->req_number)
            ->greeting('Hello,')
            ->line('You have a pending requisition with No.: ' . $this->requisition->req_number)
            ->action('View Requisition', url('/requisition/view/' . $this->requisition->req_number))
            ->line('Thank you.');
    }
} 