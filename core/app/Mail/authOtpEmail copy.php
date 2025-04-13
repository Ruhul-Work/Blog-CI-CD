<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class authOtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $otp; // Add a property to store the OTP

    /**
     * Create a new message instance.
     *
     * @param string $otp
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp; // Store the OTP
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return (new Envelope())

            ->subject(' Registration  OTP Email');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('mail.registration_otp')
            ->with(['otp' => $this->otp]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
