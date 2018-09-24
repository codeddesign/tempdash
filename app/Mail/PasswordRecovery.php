<?php

namespace App\Mail;

use App\Models\AppUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordRecovery extends Mailable
{
    use Queueable, SerializesModels;

    /** @var AppUser */
    protected $app_user;

    /**
     * Create a new message instance.
     *
     * @param AppUser $user
     */
    public function __construct(AppUser $user)
    {
        $this->app_user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
            ->from(config('business.verify_email_sender'), config('business.business_name'))
            ->view('email.password_recovery')
            ->with([
                'app_user' => $this->app_user,
                'password_verify_url' => route('auth_init_password_recov', ['token' => $this->app_user->token])
            ]);
    }
}
