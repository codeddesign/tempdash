<?php

namespace App\Mail;

use App\Models\AppUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Auth;

class UserAddWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var AppUser */
    protected $app_user;

    /**
     * Create a new message instance.
     *
     * @param AppUser $appUser
     */
    public function __construct(AppUser $appUser) {
        $this->app_user = $appUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $business_name = config('business.business_name');
        return $this->from(config('business.verify_email_sender'), $business_name)
            ->subject('Welcome to ' . $business_name . '!')
            ->view('email.user_add_welcome')
            ->with([
                'business_name' => $business_name,
                'user' => $this->app_user,
                'current_user' => Auth::getCurrentUser(),
                'verification_url' => route('user_validate_email', ['token' => $this->app_user->token])
            ]);
    }
}
