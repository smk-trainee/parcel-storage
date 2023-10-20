<?php

declare(strict_types=1);

namespace App\Enum;

enum SearchTypeEnum: string
{
    public const CASES = ['sender_phone', 'receiver_fullname'];

    case SENDER_PHONE = 'sender_phone';
    case RECEIVER_FULLNAME = 'receiver_fullname';
}
