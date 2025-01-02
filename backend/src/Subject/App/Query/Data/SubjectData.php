<?php
declare(strict_types=1);

namespace App\Subject\App\Query\Data;

use App\Common\Uuid\UuidInterface;

readonly class SubjectData
{
	public function __construct(
		public UuidInterface $id,
		public string        $name,
	)
	{
	}
}