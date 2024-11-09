<?php
declare(strict_types=1);

namespace App\Subject\Domain\Model;

class Subject
{
	public function __construct(
		private readonly string $subjectId,
		private string          $name,
	)
	{
	}

	public function getSubjectId(): string
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