<?php
declare(strict_types=1);

namespace App\User\Domain\Model;

enum UserRole: int
{
    case OWNER = 1;
    case ADMIN = 2;
    case TEACHER = 3;
    case STUDENT = 4;
}
