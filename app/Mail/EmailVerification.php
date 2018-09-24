<?php

namespace App\Mail;

use App\Models\AppUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class EmailVerification
 * @package App\Mail
 */
class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /** @var AppUser */
    protected $app_user;

    /**
     * Create a new message instance.
     *
     * @param AppUser $app_user
     */
    public function __construct(AppUser $app_user)
    {
        $this->app_user = $app_user;
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
            ->subject($business_name . ' Email Verification For ' . $this->app_user->email)
            ->view('email.email_verification')
            ->with([
                'business_name' => $business_name,
                'user' => $this->app_user,
                'verification_url' => route('user_validate_email', ['token' => $this->app_user->token])
            ]);
    }
}
