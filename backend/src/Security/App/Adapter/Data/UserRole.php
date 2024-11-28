<?php
declare(strict_types=1);

namespace App\Security\App\Adapter\Data;

enum UserRole: string
{
	case OWNER = 'ROLE_OWNER';
	case ADMIN = 'ROLE_ADMIN';
	case TEACHER = 'ROLE_TEACHER';
	case STUDENT = 'ROLE_STUDENT';
}
