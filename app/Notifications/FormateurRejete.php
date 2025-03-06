<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormateurRejete extends Notification
{
    use Queueable;

    /**
     * Créer une nouvelle instance de notification.
     */
    public function __construct()
    {
        //
    }

    /**
     * Obtenir les canaux de livraison de la notification.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];  // Utiliser le canal mail
    }

    /**
     * Obtenir la représentation du mail de la notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Votre candidature en tant que formateur a été rejetée') // Sujet personnalisé
                    ->line('Nous regrettons de vous informer que votre candidature en tant que formateur n\'a pas été retenue.')  // Ligne 1 du message
                    ->line('Nous vous remercions pour l\'intérêt que vous portez à notre programme de formation.')  // Ligne 2 du message
                    ->line('Malheureusement, nous avons décidé de ne pas poursuivre votre candidature pour cette fois-ci.')  // Ligne 3 du message
                    ->line('Nous vous souhaitons le meilleur dans vos futurs projets.')  // Ligne 4 du message
                    ->line('Merci de votre compréhension.');  // Ligne finale du message
    }

    /**
     * Obtenir la représentation sous forme de tableau de la notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
