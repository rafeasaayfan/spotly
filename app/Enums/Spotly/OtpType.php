<?php

namespace App\Enums\Spotly;

enum OtpType: string
{
    case DELETE_WEBSITE = 'delete_website';
    case VERIFY_WEBSITE_EMAIL = 'verify_website_email';
}
