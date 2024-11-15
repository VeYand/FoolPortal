<?php
declare(strict_types=1);

namespace App\Subject\App\Query\Data;

readonly class SubjectData
{
	public function __construct(
		public string $id,
		public string $name,
	)
	{
	}
}