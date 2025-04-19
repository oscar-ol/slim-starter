<?php

use App\Exceptions\MailException;
use App\Services\Config;
use App\Services\Mailer;
use PHPMailer\PHPMailer\PHPMailer;

beforeEach(function () {
    $this->mockMailer = $this->createMock(PHPMailer::class);
    $this->mockMailer->method('send')->willReturn(true);

    Config::set('mail.host', 'smtp.example.com');
    Config::set('mail.smtp_auth', true);
    Config::set('mail.username', 'user@example.com');
    Config::set('mail.password', 'password');
    Config::set('mail.encryption', 'tls');
    Config::set('mail.port', 587);
    Config::set('mail.from_address', 'no-reply@example.com');
    Config::set('mail.from_name', 'Example');

    $this->mailer = new Mailer($this->mockMailer);
});

it('sends an email successfully', function () {
    $result = $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'test@example.com'
    );

    expect($result)->toBeTrue();
});

it('throws an exception for invalid recipient email', function () {
    $this->expectException(MailException::class);
    $this->expectExceptionMessage('Invalid email address: invalid-email');

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'invalid-email'
    );
});

it('throws an exception for missing configuration', function () {
    Config::set('mail.host', null);

    $this->expectException(MailException::class);
    $this->expectExceptionMessage('Missing required mail configuration: mail.host');

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'test@example.com'
    );
});

it('throws an exception for missing attachment', function () {
    $this->expectException(MailException::class);
    $this->expectExceptionMessage('Attachment not found: non-existent-file.txt');

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'test@example.com',
        'non-existent-file.txt'
    );
});

it('throws an exception when mailer configuration fails', function () {
    $this->mockMailer->method('setFrom')->willThrowException(new \Exception('Configuration error'));

    $this->expectException(MailException::class);
    $this->expectExceptionMessage('Failed to send email: Configuration error');

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'test@example.com'
    );
});

it('throws an exception when mailer send fails', function () {
    $this->mockMailer->method('send')->willThrowException(new \Exception('Send error'));

    $this->expectException(MailException::class);
    $this->expectExceptionMessage('Failed to send email: Send error');

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'test@example.com'
    );
});

it('throws an exception for invalid mail port configuration', function () {
    Config::set('mail.port', 'invalid-port');

    $this->expectException(MailException::class);
    $this->expectExceptionMessage('Invalid mail configuration: MAIL_PORT must be a positive number.');

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'test@example.com'
    );
});

it('throws an exception when attachment path is a directory', function () {
    $directoryPath = __DIR__;

    $this->expectException(MailException::class);
    $this->expectExceptionMessage("Attachment path is a directory, not a file: $directoryPath");

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'test@example.com',
        $directoryPath
    );
});

it('throws an exception when no recipients are provided', function () {
    $this->expectException(MailException::class);
    $this->expectExceptionMessage('No valid recipients provided.');

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        ''
    );
});

it('throws an exception for invalid email addresses', function () {
    $this->expectException(MailException::class);
    $this->expectExceptionMessage('Invalid email address: invalid-email');

    $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'invalid-email'
    );
});

it('attaches a file successfully', function () {
    $attachmentPath = base_path('tests/Unit/test-attachment.txt');
    file_put_contents($attachmentPath, 'Test content');

    $this->mockMailer->expects($this->once())
        ->method('addAttachment')
        ->with(
            $this->callback(function ($path) use ($attachmentPath) {
                return realpath($path) === realpath($attachmentPath);
            }),
            'test-attachment.txt'
        );

    $result = $this->mailer->handle(
        'Test Subject',
        'Test Body',
        'test@example.com',
        'tests/Unit/test-attachment.txt'
    );

    expect($result)->toBeTrue();

    unlink($attachmentPath);
});
