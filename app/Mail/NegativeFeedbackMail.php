<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NegativeFeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $member;
    public $campaign;
    public $branch;
    public $brand;
    public $mailClassificationType;
    public $mailClassification;
    public $mailAdvisor;
    public $request;
    public $date;
    public $time;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact, $member, $campaign, $branch, $brand, $mailClassificationType, $mailClassification, $mailAdvisor, $request)
    {
        $this->contact = $contact;
        $this->member = $member;
        $this->campaign = $campaign;
        $this->branch = $branch;
        $this->brand = $brand;
        $this->mailClassificationType = $mailClassificationType;
        $this->mailClassification = $mailClassification;
        $this->mailAdvisor = $mailAdvisor;
        $this->request = $request;
        $this->date = now()->toDateString();
        $this->time = now()->toTimeString();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.negative-feedback-mail')->subject( ((isset($this->contact->customer_description)) ? $this->contact->customer_description : 'N/A') . ' ' . ((isset($this->campaign->name)) ? $this->campaign->name : 'N/A') . ' Negative VOC notification');

    }
}
