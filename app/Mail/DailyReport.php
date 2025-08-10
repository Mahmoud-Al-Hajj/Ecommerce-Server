<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $orders;
    public $totalRevenue;
    public $reportDate;

    public function __construct($orders, $totalRevenue, $reportDate)
    {
        $this->orders = $orders;
        $this->totalRevenue = $totalRevenue;
        $this->reportDate = $reportDate;
    }


    public function build()
    {
        $orderList = '';
        foreach ($this->orders as $order) {
            $orderList .= '<li>Order ID: ' . $order->id .
                ' - Total: ' . number_format($order->total_price, 2) .
                ' - Status: ' . $order->status . '</li>';
        }

        return $this->subject('Daily Report for ' . $this->reportDate)
                    ->html(
                        '<h1>Daily Report for ' . $this->reportDate . '</h1>' .
                        '<p>Total Revenue: ' . number_format($this->totalRevenue, 2) . '</p>' .
                        '<h2>Orders:</h2>' .
                        '<ul>' . $orderList . '</ul>'
                    );
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
