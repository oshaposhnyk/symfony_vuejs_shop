<?php

namespace App\Utils\Mailer;

use App\Utils\Mailer\DTO\MailerOptions;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerSender
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendTemplatedEmail(MailerOptions $mailerOptions): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->to($mailerOptions->getRecipient())
            ->subject($mailerOptions->getSubject())
            ->htmlTemplate($mailerOptions->getHtmlTemplate())
            ->context($mailerOptions->getContext());

        if ($mailerOptions->getCc()) {
            $email->cc($mailerOptions->getCc());
        }

        try {
            $this->mailer->send($email);

        } catch (TransportExceptionInterface $exception) {
            $this->logger->critical($mailerOptions->getSubject(), [
                'errorText' => $exception->getTraceAsString()
            ]);

            $systemMailerOption = new MailerOptions();
            $systemMailerOption->setText($exception->getMessage());

            $this->sendSystemEmail($systemMailerOption);
        }

        return $email;

    }

    private function sendSystemEmail(MailerOptions $options): Email
    {
        $options
            ->setSubject('[Exception] An error occured while sending the letter')
            ->setRecipient('admin@test.com');

        $email = (new Email())
            ->to($options->getRecipient())
            ->subject($options->getSubject())
            ->text($options->getText());

        try {
            $this->mailer->send($email);

        } catch (TransportExceptionInterface $exception) {
            $this->logger->critical($mailerOptions->getSubject(), [
                'errorText' => $exception->getTraceAsString()
            ]);
        }

        return $email;

    }

}