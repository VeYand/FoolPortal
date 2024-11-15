<?php
declare(strict_types=1);

namespace App\Subject\App\Adapter\Data;

enum UserRole: int
{
	case OWNER = 0;
	case ADMIN = 1;
	case TEACHER = 2;
	case STUDENT = 3;
}