<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Remove the ShouldQueue implementation to make it run synchronously
class WorkerAccountCreated extends Notification
{
    use Queueable;

    protected $password;

    /**
     * Create a new notification instance.
     *
     * @param string $password
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Les détails de votre nouveau compte')
            ->greeting('Bonjour ' . $notifiable->first_name . '!')
            ->line('Un compte a été créé pour vous.')
            ->line('Vos identifiants de connexion sont :')
            ->line('Email: ' . $notifiable->email)
            ->line('Mot de passe: ' . $this->password)
            ->line('Veuillez vous connecter et modifier votre mot de passe dès que possible.')
            ->action('Connectez-vous maintenant', url('/login'))
            ->line('Merci de faire partie de notre équipe !');
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
    public function toBroadcast($notifiable)
    {
        return [
            'message' => 'Un compte a été créé pour vous.',
            'email' => $notifiable->email,
            'password' => $this->password,
        ];
    }
}