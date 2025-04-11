<?php

namespace App\Mail;

use App\Models\Formation;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $formation;
    protected $payment;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Formation $formation
     * @param Payment $payment
     */
    public function __construct(User $user, Formation $formation, Payment $payment)
    {
        $this->user = $user;
        $this->formation = $formation;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirmation de paiement - ' . config('app.name'))
            ->view('emails.payment.success')
            ->with([
                'user' => $this->user,
                'formation' => $this->formation,
                'payment' => $this->payment,
                'date' => $this->payment->created_at->format('d/m/Y'),
                'time' => $this->payment->created_at->format('H:i'),
            ]);
    }
}