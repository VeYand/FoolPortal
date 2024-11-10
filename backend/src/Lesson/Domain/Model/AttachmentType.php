<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

enum AttachmentType: int
{
	case FILE = 0;

	case IMAGE = 1;
}
