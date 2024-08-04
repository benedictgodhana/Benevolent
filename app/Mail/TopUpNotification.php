<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TopUpNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $amount;
    public $employeeNumber;

    public function __construct($member, $amount)
    {
        $this->member = $member;
        $this->amount = $amount;

        // Fetch employee number from the related UserProfile model
        $this->employeeNumber = $member->profile->employeeNo; // Assuming 'profile' is the relationship method
    }

    public function build()
    {
        return $this->view('emails.topup_notification')
                    ->with([
                        'memberName' => $this->member->name,
                        'amount' => $this->amount,
                        'employeeNumber' => $this->employeeNumber,
                    ]);
    }
}
