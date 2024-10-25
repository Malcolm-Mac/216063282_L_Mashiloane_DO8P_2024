<?php

namespace App\Domain\User\Enums;

enum RoleNames: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
}
