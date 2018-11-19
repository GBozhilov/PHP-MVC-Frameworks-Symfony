<?php

namespace Services\Mail;


class SmtpMailService implements MailServiceInterface
{

    public function sendMAil(): void
    {
        echo 'SMTP Mail service resolved :)' .PHP_EOL;
    }
}