<?php
declare(strict_types=1);

namespace App\Lesson\Domain\Model;

class Attachment
{
	public function __construct(
		private readonly string $attachmentId,
		private string          $name,
		private ?string         $description,
		private readonly string $path,
		private readonly string $extension,
	)
	{
	}

	public function getAttachmentId(): string
	{
		return $this->attachmentId;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getExtension(): string
	{
		return $this->extension;
	}
}