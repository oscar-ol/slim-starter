<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\MailException;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mailer;

    public function __construct(?PHPMailer $mailer = null)
    {
        $this->mailer = $mailer ?? new PHPMailer(true);
    }

    public function handle(string $subject, string $body, string $to, ?string $attachmentPath = null): bool
    {
        $this->validateConfiguration();
        $recipients = $this->validateRecipients($to);

        try {
            $mail = $this->configureMailer();
            $this->addRecipients($mail, $recipients);
            $this->addAttachment($mail, $attachmentPath);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            return $mail->send();
        } catch (\Exception $e) {
            throw new MailException('Failed to send email: ' . $e->getMessage());
        }
    }

    private function validateConfiguration(): void
    {
        $requiredConfig = [
            'mail.host',
            'mail.smtp_auth',
            'mail.username',
            'mail.password',
            'mail.encryption',
            'mail.port',
            'mail.from_address',
            'mail.from_name',
        ];

        foreach ($requiredConfig as $key) {
            if (is_null(config($key))) {
                throw new MailException("Missing required mail configuration: $key");
            }
        }

        if ((int)config('mail.port') <= 0) {
            throw new MailException('Invalid mail configuration: MAIL_PORT must be a positive number.');
        }
    }

    /**
     * @return array<string>
     */
    private function validateRecipients(string $to): array
    {
        /** @var array<string> $recipients */
        $recipients = array_filter(array_map('trim', explode(',', $to)));
        if ($recipients === []) {
            throw new MailException('No valid recipients provided.');
        }

        foreach ($recipients as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new MailException("Invalid email address: $email");
            }
        }

        return $recipients;
    }

    private function configureMailer(): PHPMailer
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = config('mail.host');
        $this->mailer->SMTPAuth = filter_var(config('mail.smtp_auth'), FILTER_VALIDATE_BOOLEAN);
        $this->mailer->Username = config('mail.username');
        $this->mailer->Password = config('mail.password');
        $this->mailer->SMTPSecure = config('mail.encryption');
        $this->mailer->Port = (int)config('mail.port');
        $this->mailer->setFrom(config('mail.from_address'), config('mail.from_name'));

        return $this->mailer;
    }

    /**
     * @param  array<string>  $recipients
     */
    private function addRecipients(PHPMailer $mail, array $recipients): void
    {
        /** @var string $address */
        foreach ($recipients as $address) {
            $mail->addAddress($address);
        }
    }

    private function addAttachment(PHPMailer $mail, ?string $attachmentPath): void
    {
        if ($attachmentPath && is_dir($attachmentPath)) {
            throw new MailException("Attachment path is a directory, not a file: $attachmentPath");
        }

        if ($attachmentPath && file_exists($attachmentPath)) {
            $fileName = basename($attachmentPath);
            $mail->addAttachment($attachmentPath, $fileName);
        } elseif ($attachmentPath !== null && $attachmentPath !== '' && $attachmentPath !== '0') {
            throw new MailException("Attachment not found: $attachmentPath");
        }
    }
}
