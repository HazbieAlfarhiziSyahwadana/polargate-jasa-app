<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $pembayaran;
    public $type; // 'verified', 'rejected', 'dp_verified', 'pelunasan_verified'

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice, Pembayaran $pembayaran, string $type)
    {
        $this->invoice = $invoice;
        $this->pembayaran = $pembayaran;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'dp_verified' => 'Pembayaran DP Berhasil Diverifikasi - ' . $this->invoice->nomor_invoice,
            'pelunasan_verified' => 'Pembayaran Pelunasan Berhasil Diverifikasi - ' . $this->invoice->nomor_invoice,
            'rejected' => 'Pembayaran Ditolak - ' . $this->invoice->nomor_invoice,
            default => 'Invoice ' . $this->invoice->nomor_invoice,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'invoice' => $this->invoice,
                'pembayaran' => $this->pembayaran,
                'type' => $this->type,
                'client' => $this->invoice->pesanan->client,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generate PDF invoice
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $this->invoice,
        ])->setPaper('a4');

        return [
            Attachment::fromData(fn () => $pdf->output(), 'invoice-' . $this->invoice->nomor_invoice . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}