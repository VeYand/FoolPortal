<?php
declare(strict_types=1);

namespace App\Security\App\Adapter\Data;

enum UserRole: string
{
	case OWNER = 'OWNER';
	case ADMIN = 'ADMIN';
	case TEACHER = 'TEACHER';
	case STUDENT = 'STUDENT';
}
