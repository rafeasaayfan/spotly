<?php

namespace App\Enums\Websites\Restaurant;

enum MenuItemGroupPriceType: string
{
    case INCREASE = 'increase';
    case DEACREASE = 'deacrease';
    case CUSTOM = 'custom';
}
