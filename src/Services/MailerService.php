<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerService
{

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;


    /**
     * @param MailerInterface $mailer
     * @param Environment $twig
     */
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }


    /**
     * @param string  $subject
     * @param string $from
     * @param string $to
     * @param string $template
     * 
     */
    public function send($subject, $from, $to, $template)
    {

        $email = (new Email())
            ->from($from)
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($this->twig->render($template), 'text/html');

        $this->mailer->send($email);
    }
}