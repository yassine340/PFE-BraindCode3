<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormateurValidé extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // Pas besoin de paramètres pour l'instant, mais vous pouvez en ajouter si nécessaire.
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];  // Utiliser le canal email pour envoyer la notification
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Félicitations ! Vous êtes maintenant un formateur validé')  // Sujet de l'email
                    ->line('Félicitations ! Vous avez été validé en tant que formateur.')  // Contenu principal du mail
                    ->line('Nous sommes heureux de vous annoncer que vous faites maintenant partie de notre équipe de formateurs.')  // Complément d'information
                    ->line('Vous pouvez maintenant commencer à collaborer avec nous et offrir vos services.')  // Rappel sur la collaboration
                    ->action('Voir votre profil', url('/DashboardFormateur'))  // Un bouton avec un lien vers le profil du formateur ou un autre lien pertinent
                    ->line('Merci de faire partie de notre équipe et à très bientôt !');  // Message de fin
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            // Vous pouvez ajouter des données ici pour la notification, par exemple : 'status' => 'validé'
        ];
    }
}
