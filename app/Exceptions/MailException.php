<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class MailException extends Exception
{
    public function __construct(string $message = 'Error sending email. Please try again later.', int $code = 500, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
