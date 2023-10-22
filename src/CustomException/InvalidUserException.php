<?php

declare(strict_types=1);

namespace App\CustomException;

class InvalidUserException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = $message;
        $this->code = $code;
    }
}
