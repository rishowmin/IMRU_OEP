<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Mime\Email;

class AdminResetPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Reset Admin Password')
            ->view('emails.admin-password-reset', [
                'name' => $notifiable->first_name ?: 'Admin',
                'resetUrl' => $this->resetUrl($notifiable),
                'expiresIn' => Config::get('auth.passwords.admins.expire', 60),
                'appName' => Config::get('app.name'),
            ])
            ->withSymfonyMessage(function (Email $email) {
                $email->embedFromPath(
                    public_path('assets/admin/img/brand/logo_wh.png'),
                    'imru-oep-logo.png',
                    'image/png'
                );

                $inlineParts = $email->getAttachments();
                $logo = end($inlineParts);
                $logo->setContentId('imru-oep-logo@imru-oep');
            });
    }

    protected function resetUrl($notifiable)
    {
        return url(route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
