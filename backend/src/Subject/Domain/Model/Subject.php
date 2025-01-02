<?php
declare(strict_types=1);

namespace App\Subject\Domain\Model;

use App\Common\Uuid\UuidInterface;

class Subject
{
	public function __construct(
		private readonly UuidInterface $subjectId,
		private string                 $name,
	)
	{
	}

	public function getSubjectId(): UuidInterface
	{
		return $this->subjectId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}
}