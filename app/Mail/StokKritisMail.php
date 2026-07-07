<?php

namespace App\Mail;

use App\Models\Barang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StokKritisMail extends Mailable
{
    use Queueable, SerializesModels;

    public Barang $barang;
    public string $triggerUser;

    /**
     * @param Barang $barang         Barang yang stoknya kritis
     * @param string $triggerUser    Nama pengguna yang memicu
     */
    public function __construct(Barang $barang, string $triggerUser = 'Sistem')
    {
        $this->barang      = $barang;
        $this->triggerUser = $triggerUser;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Peringatan Stok Kritis – ' . $this->barang->nama_barang,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.stok-kritis',
            with: [
                'barang'      => $this->barang,
                'triggerUser' => $this->triggerUser,
                'appName'     => config('app.name'),
                'appUrl'      => config('app.url'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
