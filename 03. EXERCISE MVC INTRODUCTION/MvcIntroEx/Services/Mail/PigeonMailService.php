<?php

namespace Services\Mail;


class PigeonMailService implements MailServiceInterface
{
    public function sendMAil(): void
    {
        echo 'Send long flying mail via a pigeon :)' .PHP_EOL;
    }
}