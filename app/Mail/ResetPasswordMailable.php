<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $firma = Setting::where('name', 'firma')->get();
        $firmas_nos = '';
        if(sizeof($firma) > 0){
            $firmas_nos = $firma[0]->value;
        }
        return $this->subject("Paroles atjaunošana")->view('reset-password', ['firma_nos' => $firmas_nos]);
    }

}
