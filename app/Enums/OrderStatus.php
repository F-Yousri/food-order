<?php

namespace App\Enums;

enum OrderStatus: int
{
    case Preparing = 1;
    case Completed = 2;
    case Failed = 3;
}
