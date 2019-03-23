<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailChange extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $_oldEmail;

    /**
     * @var string
     */
    private $_newEmail;

    /**
     * Create a new message instance.
     *
     * @param string $oldEmail
     * @param string $newEmail
     */
    public function __construct(string $oldEmail, string $newEmail)
    {
        $this->_oldEmail = $oldEmail;
        $this->_newEmail = $newEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.email')
            ->subject(config('app.name') . " - змінився email")
            ->to($this->_oldEmail)
            ->with([
                'email' => $this->_newEmail,
            ]);
    }
}
