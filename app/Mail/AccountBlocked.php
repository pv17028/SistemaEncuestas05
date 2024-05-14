<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\BloqueoUsuario;

class AccountBlocked extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $bloqueo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, BloqueoUsuario $bloqueo)
    {
        $this->user = $user;
        $this->bloqueo = $bloqueo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.account_blocked');
    }
}