<?php

namespace App\Enums\Websites;

enum UserRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case USER = 'user';
}