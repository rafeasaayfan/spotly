<?php

namespace App\Enums\Spotly;

enum PlanDuration: string
{
    case FREE_THREE_DAYS = 'free_three_days';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
}