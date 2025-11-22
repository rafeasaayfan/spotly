<?php

namespace App\Enums\Spotly;

enum WebsiteStatus: string
{
    case PENDING = 'pending';
    case DENIED = 'denied';
    case APPROVED = 'approved';
}