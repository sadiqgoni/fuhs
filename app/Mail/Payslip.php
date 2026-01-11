<?php

namespace App\Mail;

use App\Models\EmployeeProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class Payslip extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'FUHSIO Payroll for '.Carbon::parse($this->data['month_from'])->format('F,Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
//        return new Content(
//            markdown: 'mail.payslip',
//        );
//        dd('hy');
        $employee=EmployeeProfile::where('payroll_number',$this->data['payroll_number'])->first();
        return
            $this->from(config('MAIL_USERNAME'))
                ->to($employee->email)
                ->subject('FUHSIO Payroll')
                ->markdown('mail.payslip',['data'=>$this->data,'employee'=>$employee]);
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
