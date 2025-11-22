<?php

namespace App\Enums\Websites\Ecommerce;

enum CartStatus: string
{
    case PENDING = 'pending';
    case CHECKED_OUT = 'checked_out';
    case ABANDONED = 'abandoned';
}