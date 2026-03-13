<?php

namespace App\Mail;

use App\Models\CustomerScore;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SuggestionProductsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public CustomerScore $score,
        public Collection $products,
        public string $reason = 'auto'
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Gợi ý sản phẩm dành riêng cho bạn từ BabyMart Plus',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.suggestions.personalized',
        );
    }
}
