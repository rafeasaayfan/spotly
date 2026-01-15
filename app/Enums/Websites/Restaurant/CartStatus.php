<?php

namespace App\Enums\Websites\Restaurant;

enum CartStatus: string
{
    case PENDING = 'pending';
    case CHECKED_OUT = 'checked_out';
    case ABANDONED = 'abandoned';
}